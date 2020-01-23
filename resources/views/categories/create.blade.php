@extends('layouts.app')

@section('content')
 
   <div class="card card-default">
     <div class="card-header">
     {{isset($categories) ? 'Edit Category' : 'Create category'}}
     </div>

     <div class="card-body">

     @if($errors->any())
       <div class="alert alert-danger">
         <ul class="list-group">
            @foreach($errors->all() as $error)
              <li class="list-group-item text-danger">
                {{$error}}
              </li>
            @endforeach
         </ul>  
       </div>
     @endif
     <form action="{{ isset($categories) ? route('categories.update', $categories->id) : route('categories.store') }}" method="POST">

       @csrf

       @if(isset($categories))
         @method('PUT')
       @endif
       <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" class="form-control" name="name" 
          value="{{ isset($categories) ? $categories->name : ''}}"
          >
       </div>
  
       <div class="form-group">
         <button class="btn btn-success">
         
           {{ isset($categories) ? 'Update Category': 'Add Category'}}
         
         </button>
       </div>
     </form>
     </div>
   </div>

@endsection