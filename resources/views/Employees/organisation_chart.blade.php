@extends('layouts.main_layout')
@section('page_dependencies')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stop
@section('content')
    <br>
    <br>
    <br>
    <br>
    <div class="container overflow-auto text-nowrap overflow-auto ">
        <ol class="level-2-wrapper" id="columns">
            <li class="sublevel">
                <h4 class="level-2 rectangle">
                    <br>
                    <img src="{{ (!empty(\App\HRPerson::getDirectorDetails(1)->profile_pic)) ? asset('storage/avatars/'.\App\HRPerson::getDirectorDetails(1)->profile_pic)  : ((\App\HRPerson::getDirectorDetails(1)->gender === 0) ? $f_silhouette : $m_silhouette)}} "
                         class="rounded-corners" alt="Profile" height="30" width="30">
                    <br>
                    <br>
                    <div>{{ \App\HRPerson::getDirectorDetails(1)->first_name . ' ' . \App\HRPerson::getDirectorDetails(2)->surname }} </div>
                    <br>
                    <br>
                </h4>
                <ol class="level-3-wrapper">
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(3)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(4)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(2)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        @foreach(\App\DivisionLevelTwo::getSectionDetails(1) as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>

                        @endforeach

                        @foreach(\App\HRPerson::getUsersFromTeam(1) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>

                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(2)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(2)->manager->surname}}
                            <br><br>

                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                            <br>
                        </h4>
                        @foreach(\App\DivisionLevelTwo::getSectionDetails(3) as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>
                        @endforeach


                        @foreach(\App\HRPerson::getUsersFromTeam(2) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }} </h4>
                                </li>
                            </ol>
                        @endforeach

                    </li>

                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(3)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(3)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        @foreach($teams as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>

                        @endforeach
                        @foreach(\App\HRPerson::getUsersFromTeam(3) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach


                    </li>

                </ol>
            </li>


            <li class="sublevel">
                <h4 class="level-2 rectangle">
                    <br>
                    <img src="{{ (!empty(\App\HRPerson::getDirectorDetails(1)->profile_pic)) ? asset('storage/avatars/'.\App\HRPerson::getDirectorDetails(1)->profile_pic)  : ((\App\HRPerson::getDirectorDetails(1)->gender === 0) ? $f_silhouette : $m_silhouette)}} "
                         class="rounded-corners" alt="Profile" height="30" width="30">
                    <br>
                    <br>
                    <div>{{ \App\HRPerson::getDirectorDetails(1)->first_name . ' ' . \App\HRPerson::getDirectorDetails(1)->surname }} </div>
                    <br>
                    <br>
                </h4>
                <ol class="level-3-wrapper">
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(4)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(4)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(4)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(4)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(4)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(3)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        @foreach($teams as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>

                        @endforeach

                        @foreach(\App\HRPerson::getUsersFromTeam(1) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>

                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(5)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(5)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(5)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(5)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(5)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(5)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(2)->manager->surname}}
                            <br><br>

                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                            <br>
                        </h4>
                        @foreach(\App\DivisionLevelTwo::getSectionDetails(2) as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>
                        @endforeach


                        @foreach(\App\HRPerson::getUsersFromTeam(2) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach

                    </li>

                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(6)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(6)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(6)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(6)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(6)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(6)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>

                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(3)->manager->surname}}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        @foreach($teams as $team)
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{$team->name }}
                                <br><br>
                                {{ $team->manager->first_name .  ' ' .
                                 $team->manager->surname}}
                                <br><br>
                                <img src="{{ (!empty($team->manager->profile_pic))
                                  ? asset('storage/avatars/'.$team->manager->profile_pic)  :
                                   (($team->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>

                        @endforeach
                        @foreach(\App\HRPerson::getUsersFromTeam(3) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle"> {{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach


                    </li>

                </ol>
            </li>


        </ol>
    </div>
@stop
