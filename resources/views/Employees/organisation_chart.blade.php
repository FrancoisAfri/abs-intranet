@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stop
@section('content')
    <br><br>
    <div class="callout callout-warning">
        <h4>Attention!!!</h4>
       Users with a   <i class="fa fa-star margin-r-5"></i> are Sections or Teams managers
    </div>
    <br><br>
    <div class="container vertical-scrollable">
        <ol class="level-2-wrapper" id="columns">
            <li class="sublevel">
                <h4 class="level-2 rectangle">
                    <br>
                    <img class="img-circle img-bordered-sm"
                         src="{{ (!empty(\App\HRPerson::getDirectorDetails(100)->profile_pic)) ? asset('storage/avatars/'.\App\HRPerson::getDirectorDetails(100)->profile_pic)
: ((\App\HRPerson::getDirectorDetails(100)->gender === 0) ? $f_silhouette : $m_silhouette)}} "
                         class="rounded-corners" alt="Profile" height="30" width="30">
                    <br>
                    <br>
                    <strong><i class="fa fa-pencil margin-r-5 "></i> <span style="color: white">Finance Director</span></strong>
                    <br>
                    <br>
                    <div style="color: white">{{ \App\HRPerson::getDirectorDetails(100)->first_name . ' ' . \App\HRPerson::getDirectorDetails(100)->surname }} </div>
                    <br>
                    <br>
                </h4>
                <ol class="level-3-wrapper">
                    {{--  department--}}
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">

                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(2)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(2)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(2)->manager->surname}}</span>
                            <br>
                            <br>
                            <span class="label label-success ">{{ \App\DivisionLevelFour::getDepartmentDetails(2)->manager->jobTitle->name }}</span>
                        </h4>

                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(2) as $users)
                            <ol class="level-4-wrapper">
                                <li>

                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>


                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name: ''  }}</span>
                                        <br><br>
                                        @if( !empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                        <span class="badge label-warning" data-toggle="tooltip"
                                           title="Section - {{$users->section->name }}"
                                           class="users-list-name">{{ $users->section->name }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                        <span class="badge label-warning" data-toggle="tooltip"
                                              title="Team - {{$users->team->name }}"
                                              class="users-list-name"> {{ $users->team->name }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif

                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>

                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">

                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(4)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(4)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(4)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(4)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(4)->manager->surname}}</span>
                            <br>
                            <br>
                            <span class="label label-success ">{{ \App\DivisionLevelFour::getDepartmentDetails(4)->manager->jobTitle->name }}</span>
                        </h4>

                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(4) as $users)
                            <ol class="level-4-wrapper">
                                <li>

                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>
                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name: ''  }}</span>
                                        {{--                                        @if( $users->id  == $users->section->manager_id)--}}
                                        <br><br>
                                        @if(!empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{$users->section->name }}"
                                                  class="users-list-name">{{ $users->section->name }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{$users->team->name }}"
                                                  class="users-list-name"> {{ $users->team->name }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif
                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">

                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(3)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(3)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(3)->manager->surname}}</span>
                            <br>
                            <br>
                            <span class="label label-success ">{{ \App\DivisionLevelFour::getDepartmentDetails(3)->manager->jobTitle->name }}</span>
                        </h4>
                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(3) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>
                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name: ''  }}</span>
                                        {{--                                        @if( $users->id  == $users->section->manager_id)--}}
                                        <br><br>
                                        @if(!empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{$users->section->name }}"
                                                  class="users-list-name">{{ $users->section->name }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{$users->team->name }}"
                                                  class="users-list-name"> {{ $users->team->name }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif

                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>

                </ol>
            </li>
            <li class="sublevel">
                <h4 class="level-2 rectangle">
                    <br>
                    <img class="img-circle img-bordered-sm"
                         src="{{ (!empty(\App\HRPerson::getDirectorDetails(18)->profile_pic)) ? asset('storage/avatars/'.\App\HRPerson::getDirectorDetails(18)->profile_pic)
: ((\App\HRPerson::getDirectorDetails(18)->gender === 0) ? $f_silhouette : $m_silhouette)}} "
                         class="rounded-corners" alt="Profile" height="30" width="30">
                    <br>
                    <br>
                    <strong><i class="fa fa-pencil margin-r-5"></i> <span style="color: white">Managing Director</span></strong>
                    <br>
                    <br>
                    <div style="color: white">{{ \App\HRPerson::getDirectorDetails(18)->first_name . ' ' . \App\HRPerson::getDirectorDetails(18)->surname }} </div>
                    <br>
                    <br>
                </h4>
                <ol class="level-3-wrapper">
                    {{--  department--}}
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">

                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(6)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(6)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(6)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(6)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(6)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(6)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(6)->manager->surname}}</span>
                            <br>
                            <br>
                            <span class="label label-success ">{{ \App\DivisionLevelFour::getDepartmentDetails(6)->manager->jobTitle->name }}</span>
                        </h4>
                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(6) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>


                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name: ''  }}</span>
                                         <br><br>
                                        @if(!empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{$users->section->name }}"
                                                  class="users-list-name">{{ $users->section->name }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{$users->team->name }}"
                                                  class="users-list-name"> {{ $users->team->name }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif

                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">

                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(5)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(5)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(5)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(5)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(5)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(5)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(5)->manager->surname}}</span>
                            <br>
                            <br>
                            <span class="label label-success ">{{ \App\DivisionLevelFour::getDepartmentDetails(5)->manager->jobTitle->name }}</span>
                        </h4>
                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(5) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>
                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name: ''  }}</span>
                                        {{--                                        @if( $users->id  == $users->section->manager_id)--}}
                                        <br><br>
                                        @if(!empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{$users->section->name }}"
                                                  class="users-list-name">{{ $users->section->name }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{$users->team->name }}"
                                                  class="users-list-name"> {{ $users->team->name }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif

                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            <a data-toggle="tooltip"
                               title="Department - {{ \App\DivisionLevelFour::getDepartmentDetails(1)->name }}"
                               class="users-list-name"
                               href="#">{{ \App\DivisionLevelFour::getDepartmentDetails(1)->name }}</a>
                            <br>
                            <img class="img-circle img-bordered-sm" src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br><br>
                            <span style="color: white">{{ \App\DivisionLevelFour::getDepartmentDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(1)->manager->surname}}</span>
                            <br>
                            <br>
							<span class="label label-success ">{{!empty(\App\DivisionLevelFour::getDepartmentDetails(1)->manager->jobTitle->name) ? \App\DivisionLevelFour::getDepartmentDetails(1)->manager->jobTitle->name : '' }}</span>
                        </h4>
                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(1) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">
                                        <img class="img-circle img-bordered-sm" src="{{ (!empty($users->profile_pic))
                                  ? asset('storage/avatars/'.$users->profile_pic)  :
                                   (($users->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                             alt="Profile"
                                             style='height: 60%; width: 60%; object-fit: contain'/>
                                        {{ $users->first_name . '' . $users->surname }}
                                        <br><br>
                                        <span class="label label-success ">{{!empty($users->jobTitle->name) ? $users->jobTitle->name : ''  }}</span>
                                        <br><br>
                                        @if(!empty($users->section->manager_id) && $users->id  == $users->section->manager_id)
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                            <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span class="badge label-warning" data-toggle="tooltip"
                                                  title="Section - {{!empty($users->section->name) ? $users->section->name : ''  }}"
                                                  class="users-list-name">{{!empty($users->section->name) ? $users->section->name : ''  }}
                                        </span>
                                        @endif
                                        <br><br>
                                        @if( !empty($users->team->manager_id) && $users->id  == $users->team->manager_id)
                                            <span  style="line-height: 33px !important"
                                                    class="badge label-warning" data-toggle="tooltip"
                                                  title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                  class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                             <i class="fa fa-star margin-r-5"></i>
                                        </span>
                                        @else
                                            <span  style="line-height: 2px !important"
                                                   class="badge label-warning" data-toggle="tooltip"
                                                   title="Team - {{!empty($users->team->name) ? $users->team->name : ''  }}"
                                                   class="users-list-name"> {{!empty($users->team->name) ? $users->team->name : ''  }}
                                        </span>
                                        @endif
                                    </h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>
                </ol>
            </li>

        </ol>
    </div>
@stop