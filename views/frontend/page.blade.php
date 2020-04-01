@extends(themePath('layouts.app'), ['banner'=>'none'])
@section('title', $title)
@section('description', $description)

@section('content')
<section>
	<div class="container">
		<div class="row mb-5">
			<div class="col-lg-12 text-center">
				<h2 class="section-heading text-uppercase">{{$page->name}}</h2>
			</div>
		</div>
		{!! $page->body !!}
	</div>
</section>
@endsection