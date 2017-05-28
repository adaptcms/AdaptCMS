@extends('layouts.base')

@section('content')
	@if(!$articles->count())
		<p>No articles posted</p>
	@else
		@foreach($articles as $article)
			<div class="col s12 m7">
    <h2 class="header">{{ $article->name }}</h2>
    <div class="card horizontal">
      <div class="card-image">
        <img src="http://lorempixel.com/100/190/nature/6">
      </div>
      <div class="card-stacked">
        <div class="card-content">
          <p>I am a very simple card. I am good at containing small bits of information.</p>
        </div>
        <div class="card-action">
          <a href="{{ route('articles.view', [ 'slug' => $article->slug ]) }}" class="btn pull-right">View Article</a>
        </div>
      </div>
    </div>
  </div>
  		@endforeach
  		
  		{{ $articles->links() }}
	@endif
@stop