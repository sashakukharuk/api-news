<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CommentFilter
{
    protected Request $request;
    protected Builder $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter) && $value !== null) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function filters(): array
    {
        return $this->request->only([
            'user_id', 
            'body', 
            'news_id', 
            'from_date', 
            'to_date',
            'user_email'
        ]);
    }

    public function news_id($value)
    {
        $this->builder->where('news_id', $value);
    }

    public function user_id($value)
    {
        $this->builder->where('user_id', $value);
    }

    public function body($value)
    {
        $this->builder->whereFullText('body', $value);
    }

    public function from_date($value)
    {
        $date = Carbon::parse($value);
        $this->builder->where('created_at', '>=', $date);
    }

    public function to_date($value)
    {
        $date = Carbon::parse($value);
        $this->builder->where('created_at', '<=', $date);
    }

    public function user_email($value)
    {
        $this->builder->whereHas('user', function ($query) use ($value) {
            $query->where('email', 'like', "%$value%");
        });
    }
}
