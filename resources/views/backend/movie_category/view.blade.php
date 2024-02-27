<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Category: {{$movie_categories['name']}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="data_details" class="tab-pane fade in active show">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><strong>Category</strong></td>
                                                        <td>{{ $movie_categories->category}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Name</strong></td>
                                                        <td>{{ $movie_categories->name ?? ''}}</td>
                                                    </tr>
                                                    @if(!empty($movie_categories->season))
                                                    <tr>
                                                        <td><strong>Season</strong></td>
                                                        <td>{{ $movie_categories->season ?? ''}}</td>
                                                    </tr>
                                                    @endif
                                                    @if (!empty($movie_categories->yt_link))
                                                    <tr>
                                                        <td><strong>Link</strong></td>
                                                        <td>
                                                                <a href="{{$movie_categories->yt_link }}" target="_blank" class="btn btn-primary fa fa-eye"></a></td>
                                                    </tr> 
                                                    @endif
                                                    <tr>
                                                        <td><strong>Genre</strong></td>
                                                        <td>
                                                            
                                                         @php
                                                             $data=json_decode($movie_categories->genre,true);
                                                            //  print_r($data);exit;
                                                            foreach ($data as $key => $value) {
                                                            if ($value == 'on' && !empty($data)) {
                                                                print_r($key.', ');
                                                            }
                                                        }
                                                         @endphp

                                                        </td>
                                                    </tr>
                                                    @if(isset($media))
                                                    <tr>
                                                        <td><strong>{{$movie_categories->category}} Image</strong></td>
                                                        <td><img src="{{$media ?? ''}}" width="150px" height="auto" alt=""></td>
                                                    </tr>
                                                    @endif 
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

