<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Foreach_;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$transformer)
    {
       $transformedInputs=[];
       foreach($request->all() as $input=>$value)
       {
            $transformedInputs[$transformer::originalAttributes($input)]=$value;
       }
       $request->replace($transformedInputs);
       $response=$next($request);

       if(isset($response->exception)&& $response->exception instanceof ValidationException)
       {
            $transformedErrors=[];
            $data=$response->getData();
            foreach($data->error as $field=>$error)
            {
                $transformedField=$transformer::transformedAttributes($field);
                $transformedErrors[$transformedField]=str_replace($field,$transformedField,$error);
            }
            $data->error=$transformedErrors;
            $response->setData($data);
       }
       return $response;
    }
}
