<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponse{
    private function successResponse($data,$code)
    {
        return response()->json($data,$code);
    }
    protected function errorResponse($message,$code)
    {
        return response()->json(['error'=>$message,'code'=>$code],$code);

    }
    protected function showAll(Collection $collection,$code=200)
    {
        if($collection->isEmpty())
        {
            return $this->successResponse(['data'=>$collection],$code);
        }
        $transformer=$collection->first()->transformer;
        $collection=$this->filterData($collection,$transformer);
        $collection=$this->sortData($collection,$transformer);
        $collection=$this->paginate($collection);
        $collection=$this->transform($collection,$transformer);
        $collection=$this->cacheData($collection);
        return $this->successResponse($collection,$code);
    }
    protected function showOne(Model $instance,$code=200)
    {
        $instance=$this->transform($instance,$instance->transformer);

        return $this->successResponse($instance,$code);
    }
    protected function showMessage($message,$code=200)
    {
        return $this->successResponse(['data'=>$message],$code);
    }

    public function transform($data,$transformer)
    {
        $transformation=fractal($data,new $transformer)->toArray();
        return $transformation;
    }

    public function sortData($collection,$transformer)
    {
        if(request()->has('sort_by'))
        {
            $attribute=$transformer::originalAttributes(request()->sort_by);
            $collection=$collection->sortBy($attribute);
        }     
        return $collection;
    }

    public function filterData($collection,$transformer)
    {
        foreach(request()->query() as $attribute=>$value)
        {
            $attribute=$transformer::originalAttributes($attribute);
            if(isset($attribute,$value))
            {
                $collection=$collection->where($attribute,$value);
            }  
        }
        return $collection;
    }
    protected function paginate($collection)
    {
        $rules=[
            'per_page'=>'integer|min:1|max:201'
        ];
        Validator::validate(request()->all(), $rules);
        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=15;
        if(request()->has('per_page'))
        {
            $perPage=(int)request()->per_page;
        }
        $result=$collection->slice(($page-1) * $perPage,$perPage)->values();
        $pagenated=new LengthAwarePaginator($result,$collection->count(),$perPage,$page,[
            'path'=>LengthAwarePaginator::resolveCurrentPage()
        ]);
        $pagenated->appends(request()->all());
        return $pagenated;

    }

    protected function cacheData($data)
    {
        $url = request()->getPathInfo();
        $queryParams=request()->query();
        ksort($queryParams);
        $queryString=http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        return Cache::remember($fullUrl, 30/60, function () use ($data){
            return $data;
        });
    }
}