@extends('layouts.admin')
@section('title', 'Tecnologies')

@section('content')
<section class="container">
  @if(session()->has('message'))
    <div class="alert alert-success">{{session()->get('message')}}</div>
  @endif
  <div class="d-flex justify-content-between align-items-center py-4">
    <h1>Tecnologies</h1>
    <a href="{{route('admin.tecnologies.create')}}" class="btn btn-primary">
      Crea nuova tecnology
    </a>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Slug</th>
        <th scope="col">Created At</th>
        <th scope="col">Update At</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($tecnologies as $tecnology)
      <tr>
      <td>{{$tecnology->id}}</td>
      <td>{{$tecnology->name}}</td>
      <td>{{$tecnology->slug}}</td>
      <td>{{$tecnology->created_at}}</td>
      <td>{{$tecnology->updated_at}}</td>
      <td>
        <div class="d-flex align-items-center gap-2">
        <a href="{{route('admin.tecnologies.show', $tecnology->slug)}}">
          <i class="fa-solid fa-eye"></i>
        </a>
        <a href="{{route('admin.tecnologies.edit', $tecnology->slug)}}">
          <i class="fa-solid fa-pen"></i>
        </a>
        <form action="{{route('admin.tecnologies.destroy', $tecnology->slug)}}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="delete-button btn fs-6 p-0" data-item-title="{{$tecnology->title}}">
          <i class="fa-solid fa-trash" style="color: #0A58CA;"></i>
          </button>

        </form>
        </div>

      </td>
      </tr>
    @endforeach


    </tbody>
  </table>
</section>
@include('admin.partials.modal-delete')
@endsection