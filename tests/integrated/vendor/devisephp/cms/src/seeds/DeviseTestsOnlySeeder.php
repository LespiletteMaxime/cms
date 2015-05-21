<?php

class DeviseTestsOnlySeeder extends DeviseSeeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->fields();
		$this->collectionInstances();
		$this->collectionSets();
        $this->globalFields();
		$this->users();
	}

	/**
	 * Fields
	 *
	 * @return void
	 */
	public function fields()
	{
        $data = array(
            array(
                'id'                        => 1,
                'page_version_id'           => 1,
                'collection_instance_id'    => null,
                'type' 				        => 'text',
                'human_name'                => 'Good-Bye',
                'key'                       => 'hello',
                'json_value'                => '{}',
            ),
            array(
                'id'                        => 2,
                'collection_instance_id'    => null,
                'page_version_id'           => 1,
                'type'                      => 'textarea',
                'human_name'                => 'Good-Bye',
                'key'                       => 'goodbye',
                'json_value'                => '{}',
            ),
            array(
                'id'                        => 3,
                'collection_instance_id'    => 1,
                'page_version_id'           => 1,
                'type'                      => 'image',
                'human_name'                => 'Key #1',
                'key'                       => 'key1',
                'json_value'                => '{"bar": "awesome"}'
            ),
        );

        DB::table('dvs_fields')->insert($data);
	}

	/**
	 * Collection instances
	 *
	 * @return void
	 */
	public function collectionInstances()
	{
		$data = array(
			array(
                'id'                  => 1,
                'collection_set_id'   => 1,
                'page_version_id' 	  => 1,
                'name'                => 'Instance #1',
                'sort' 				  => 1
			),
			array(
                'id'                  => 2,
                'collection_set_id'   => 1,
                'page_version_id' 	  => 1,
                'name'                => 'Instance #2',
                'sort' 				  => 2
			),
		);

		DB::table('dvs_collection_instances')->insert($data);
	}

	/**
	 * Collection sets
	 *
	 * @return void
	 */
	public function collectionSets()
	{
		DB::table( 'dvs_collection_sets' )->delete();

		$data = array(
			array(
                'id'	=> 1,
                'name' 	=> 'Collection Set #1',
			),
		);

		DB::table('dvs_collection_sets')->insert($data);
	}

	/**
	 * Global fields
	 *
	 * @return void
	 */
	public function globalFields()
	{
        $data = array(
            array(
                'id'          => 1,
                'language_id' => 45,
                'type'        => 'image',
                'human_name'  => 'Key #1',
                'key'         => 'key1',
                'json_value'  => '{"url": "/media/kitten.jpg"}',
            ),
        );

        DB::table('dvs_global_fields')->insert($data);
	}

    /**
     * User records
     *
     * @return void
     */
    public function users()
    {
        // adds a user records which is unactivated and created_at over 30 days ago
        $activatedUser = $this->findOrCreateRow('users', 'email', [
            'name'           => 'Devise Administrator',
            'email'          => 'noreply@devisephp.com',
            'username'       => 'deviseadmin',
            'password'       => \Hash::make('secret'),
            'activated'      => true,
            'activate_code'  => null,
            'remember_token' => null,
            'created_at'     => date('Y-m-d H:i:s', strtotime('now')),
            'deleted_at'     => null
        ]);

         // adds a user records which is unactivated and created_at over 30 days ago
        $unactivatedUser = $this->findOrCreateRow('users', 'email', [
            'name'           => 'Foo Guy',
            'email'          => 'fooguy@devisephp.com',
            'username'       => 'fooguy',
            'password'       => \Hash::make('secret'),
            'activated'      => false,
            'activate_code'  => null,
            'remember_token' => null,
            'created_at'     => date('Y-m-d H:i:s', strtotime('-2 Months')),
            'deleted_at'     => null
        ]);

        DB::table('group_user')->insert(
            [
                'group_id' => 1,
                'user_id'  => $activatedUser->id,
            ],
            [
                'group_id' => 1,
                'user_id' => $unactivatedUser->id
            ]
        );
    }

}