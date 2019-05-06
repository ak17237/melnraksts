@extends('welcome')

@if ($errors->has('validate'))
            <div class="container">
                <div class="col-md-10 col-md-offset-1">
                    <div class="alert alert-danger">
                        <ul>
                                <li>{{ $errors->first('validate') }}</li>    
                        </ul>
                    </div>
                </div>
            </div>
        @endif 
