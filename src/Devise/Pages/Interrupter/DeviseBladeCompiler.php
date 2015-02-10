<?php namespace Devise\Pages\Interrupter;

/**
 * Class DeviseBladeCompiler is used for Blade::extend()
 * and pre-renders blade views to seek out devise tags inside
 * of the blade html view
 *
 * @package Devise\Pages\Interrupter
 */
class DeviseBladeCompiler
{
    /**
     * Constructs a new DeviseBladeCompiler
     *
     * @param BlockFactory $BlockFactory
     */
	public function __construct(BlockFactory $BlockFactory)
	{
		$this->BlockFactory = $BlockFactory;
	}

	/**
	 * Compile a blade view for the data-devise html data bindings
	 *
	 * @param  string $view
	 * @return string
	 */
	public function compile($view, $compiler)
	{
		// gets the block structure for this view
		$block = $this->BlockFactory->createBlock($view);

		// no devise tags found so don't touch $view
		if (count($block->getTags()) == 0 && count($block->getModels()) == 0
			&& count($block->getModelCreators()) == 0)
		{
			return $view;
		}

		// loop through the blocks
		// for this block (exclude include type)
		// and put all tags found at the top
		// starting position inside the $view
		$view = $this->addHiddenPlaceHoldersToView($view, $block);

		// also need to replace the data devise tags
		// inside of our view with the data-dvs-...-id="stuff"
		$view = $this->replaceDataDeviseTags($view, $block);

		// we will replace instances of data-devise="$model, ..."
		// with helper functions that disect the model for
		// further use in the javascript world
		$view = $this->replaceDataDeviseModels($view, $block);

		// we will replace data-devise-create-model="..." tags
		// with a cid that can be used as a place holder
		$view = $this->replaceDataDeviseModelCreators($view, $block);

		// adds bindings to top of the view page so we
		// can do the javascript json stuff for dvsPageData
		$prepend = $this->addDeviseTagBindingsModelCreatorsAndCollections($view, $block);

		// this will be run after the thing is compiled,
		$compiler->afterCompiled[] = function($view, $compiler) use ($prepend)
		{
			return $prepend ? '<?php ' . $prepend . ' ?>' . PHP_EOL . $view : $view;
		};

		return $view;
	}

	/**
	 * Replace the data-devise="$model, ..." models in the view
     *
	 * @param  string $view
	 * @param  Block $block
	 * @return string
	 */
	protected function replaceDataDeviseModels($view, $block)
	{
		$models = $block->getModels();

		foreach ($models as $model)
		{
			$view = $model->replaceModelInView($view);
		}

		return $view;
	}

	/**
	 * Replaces data-devise-create-model="..." in the view
	 *
	 * @param  string $view
	 * @param  Block  $block
	 * @return string
	 */
	protected function replaceDataDeviseModelCreators($view, $block)
	{
		$modelCreators = $block->getModelCreators();

		foreach ($modelCreators as $modelCreator)
		{
			$view = $modelCreator->replaceModelCreateTagInView($view);
		}

		return $view;
	}

	/**
	 * Replace the data-devise="..." tags in the view
     *
	 * @param  string $view
	 * @param  Block $block
	 * @return string
	 */
	protected function replaceDataDeviseTags($view, $block)
	{
		$tags = $block->getTags();

		foreach ($tags as $tag)
		{
			$view = $tag->replaceTagInView($view);
		}

		return $view;
	}

    /**
     * Adds these devise tags to bindings and collections
     * using a large string that looks like
     *
     * <?php
     *   App::make('dvsPageData')->addBinding('keyname', 'type', ...)
     * ?>
     *
     * @param string $view
     * @param Block $block
     * @return string
     */
	protected function addDeviseTagBindingsModelCreatorsAndCollections($view, $block)
	{
		$tags = $block->getTags();

		$modelCreators = $block->getModelCreators();

		if (count($tags) == 0 && count($modelCreators) == 0) return '';

		$prepend = "";

		foreach ($tags as $tag)
		{
			$prepend .= $tag->addToDevisePageStr();
		}

		foreach ($modelCreators as $modelCreator)
		{
			$prepend .= $modelCreator->addToDevisePageStr();
		}

		return $prepend;
	}

    /**
     * This adds a hidden placeholder html to the view. It is important to
     * keep up with offsets too, so we know how much our view has grown because
     * of the extra html code we threw in for the placeholder.
     *
     * @param string $view
     * @param Block $block
     * @return string
     */
	protected function addHiddenPlaceHoldersToView($view, $block)
	{
		// when we start adding stuff to our
		// view then our positions all become
		// relative to the offset...
		$offset = 0;

		foreach ($block->getBlocks() as $child)
		{
			if ($child->type == 'block')
			{
				foreach ($child->getTags(['block', 'include']) as $tag)
				{
					$placeholder = $tag->hiddenPlaceholderStr();
					$position = $offset + $child->getStartingPosition();
					$offset += strlen($placeholder);
					$view = substr_replace($view, $placeholder, $position, 0);
				}
			}
		}

		return $view;
	}
}