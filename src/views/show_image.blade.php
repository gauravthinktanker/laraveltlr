@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJ2qPAV+xY1qibZ8PAhDQc4qB/vCtd2M49aI7w/aD/yI1qADMKFv7GfBb+AXosy" crossorigin="anonymous">
@endpush

@section('content')
<div class="content-wrapper">
    <?php $i = 1; ?>
    @if(!empty($imageUrlsByDate))
        @foreach($imageUrlsByDate as $date => $imageUrls)
            <h4>{{ $date }}</h4>
            <div class="row">
                @foreach($imageUrls as $img_data)
                    <div class="col-md-3">
                        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
                            <img src="{{ $img_data }}" class="img-thumbnail" data-toggle="modal" data-target="#imageModal{{$i}}" />
                        </div>
                        <br>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="imageModal{{$i}}" tabindex="-1" aria-labelledby="imageModalLabel{{$i}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel{{$i}}">Image {{$i}}</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ $img_data }}" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $i++; ?>
                @endforeach
            </div>
        @endforeach
    @else
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            <h4>No Record Found</h4>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3O6N8CDWgD1nZhsl8xotQKs5y7eZ6d0Zl+37Z4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8HMz1YY9LW2G3r2P4a6c6LE2f/tQF3ej4IBZK7L2z6a2gJLBYcc5qRU5mg4J" crossorigin="anonymous"></script>
@endpush
