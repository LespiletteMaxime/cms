<?php namespace Devise\Media\Encoding;

use \Services_Zencoder;
use \Services_Zencoder_Exception;
use Devise\Media\Files\FileDownloader;
use Devise\Support\Framework;

/**
 * Class ZencoderJob handles incoming requests that have been
 * completed by Zencoder. Also pushes requests to Zencoder api.
 *
 * @package Devise\Media\Encoding
 */
class ZencoderJob
{
    /**
     * @var string
     */
	protected $apiKey;

    /**
     * @var array
     */
    protected $notifications;

    /**
     * @var FileDownloader
     */
    protected $FileDownloader;

    /**
     * Create a new ZencoderJob
     *
     * @param string $apiKey
     * @param array $notifications
     * @param FileDownloader $FileDownloader
     * @param null $Event
     */
	public function __construct($apiKey, $notifications, FileDownloader $FileDownloader, Framework $Framework)
	{
		$this->apiKey = $apiKey;
		$this->notifications = $notifications;
		$this->FileDownloader = $FileDownloader;
        $this->Event = $Framework->Event;
		$this->Zencoder = new Services_Zencoder($this->apiKey);
	}

	/**
	 * Create a new job with this file path and settings
	 *
	 * For an idea of different output settings see api docs at
	 * https://app.zencoder.com/docs/api/encoding
	 *
	 * @param  string $filePath path to the video file you want to encode
	 * @param  array $settings custom settings
	 * @throws Services_Zencoder_Exception use $e->getErrors()
	 * @return Job
	 */
	public function create($filePath, $settings)
	{
		$this->assertValidSettings($settings);

		$data = [
			'input' => $filePath,
			'outputs' => $this->buildOutputSettings($filePath, $settings),
		];

		try {
			$job = $this->Zencoder->jobs->create($data);
		}
		catch (\Services_Zencoder_Exception $e) {
			// we want a better error message for our exception
			// so that's what we are doing here
			throw new InvalidEncodingSettingsException($e->getErrors()->{'0'});
		}

		$this->Event->fire('devise.encoding.zencoder.started', [$job]);

		return $job;
	}

    /**
     * Fire events when you see this job has been
     * called back
     *
     * @param $output
     * @param $storagePath
     * @return void
     */
	public function handle($output, $storagePath)
	{
		switch ($output['state'])
		{
			case 'finished':
				$filename = $output['label'];
				$fromUrl = $output['url'];
				$file = $this->FileDownloader->download($fromUrl, $storagePath, $filename);
				$this->Event->fire('devise.encoding.zencoder.finished', [$file, $output]);
			break;

			default:
				$this->Event->fire('devise.encoding.zencoder.error', [$output]);
			break;
		}
	}

    /**
     * Build up output settings for all the current settings
     * passed into as an array
     *
     * @param $filePath
     * @param $allSettings
     * @internal param array $settings
     * @return array
     */
	protected function buildOutputSettings($filePath, $allSettings)
	{
		$output = array();

		foreach ($allSettings as $index => $settings)
		{
			$settings['notifications'] = $this->notifications;
			$output[] = $settings;
		}

		return $output;
	}

	/**
     * Validate that our settings are in correct format
     *
     * @param  array $settings
     * @throws InvalidEncodingSettingsException
     * @return void
     */
	protected function assertValidSettings($settings)
	{
		if (count($settings) == 0) {
			throw new InvalidEncodingSettingsException("Must have at least one setting defined in order to start a new encoding job");
		}

		foreach ($settings as $setting)
		{
			if (!isset($setting['label']))
			{
				throw new InvalidEncodingSettingsException("Every setting must have a label");
			}

			if (!isset($setting['format']))
			{
				throw new InvalidEncodingSettingsException("Every setting must have a format");
			}
		}
	}
}