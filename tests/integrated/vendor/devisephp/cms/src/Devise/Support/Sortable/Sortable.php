<?php namespace Devise\Support\Sortable;

use Sort;

trait Sortable
{
	/**
	 * Get a new query builder for the model's table.
	 *
	 * @todo  Need to check 4.2 soft delete traits because this is different now
	 * @param  bool  $excludeDeleted
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function newQuery($excludeDeleted = true)
	{
		$builder = new EloquentBuilder($this->newBaseQueryBuilder());
		// Once we have the query builders, we will set the model instances so the
		// builder can easily access any information it may need from the model
		// while it is constructing and executing various queries against it.
		$builder->setModel($this)->with($this->with);
		if ($excludeDeleted and $this->softDelete)
		{
			$builder->whereNull($this->getQualifiedDeletedAtColumn());
		}
		return $builder;
	}
}