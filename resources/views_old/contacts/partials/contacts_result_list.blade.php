<ul class="products-list product-list-in-box">
    <!-- item -->
    @foreach($persons as $person)
        <li class="item">
            <div class="product-img">
                <img src="{{ $person->profile_pic_url }}" alt="Profile Picture">
            </div>
            <div class="product-info">
                <a href="{{ '/contacts/' . $person->id . '/edit' }}" class="product-title">{{ $person->full_name }}</a>
                <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $person->str_status }}</span><!-- </a> -->
                <span class="product-description">
                    @if(!empty($person->email))
                        <i class="fa fa-envelope-o"></i> {{ $person->email }}
                    @endif
                    @if(!empty($person->company) && !empty($person->company->name))
                        &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-building-o"></i> {{ $person->company->name }}
                    @endif
                    @if(!empty($person->id_number))
                        &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-book"></i> {{ $person->id_number }}
                    @endif
                    @if(!empty($person->passport_number))
                        &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-book"></i> {{ $person->passport_number }}
                    @endif
                </span>
            </div>
        </li>
    @endforeach
<!-- /.item -->
</ul>