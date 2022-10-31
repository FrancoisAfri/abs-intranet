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
                    <div>{{ \App\HRPerson::getDirectorDetails(1)->first_name . ' ' . \App\HRPerson::getDirectorDetails(1)->surname }} </div>
                    <br>
                    <br>
                </h4>
                <ol class="level-3-wrapper">
                        {{--  department--}}
                    <li>
                        <!-- <h3 class="level-3 rectangle">Manager A</h3> -->
                        <h4 class="level-2 rectangle pfoto">
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(1)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(1)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                            <h4 class="level-2 rectangle">Team
                                <br>
                                {{ \App\DivisionLevelTwo::getTeamDetails(1)->name }}
                                <br><br>
                                {{ \App\DivisionLevelTwo::getTeamDetails(1)->manager->first_name .  ' ' .
                                 \App\DivisionLevelTwo::getTeamDetails(1)->manager->surname}}
                                <br><br>
                                {{ \App\DivisionLevelTwo::getTeamDetails(1)->hrRoles->description }}
                                <br><br>
                                <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                     class="card-img-top" alt="Profile"
                                     style='height: 40%; width: 40%; object-fit: contain'/>
                                <br>
                            </h4>

                        {{-- users--}}
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
                            {{ \App\DivisionLevelFour::getDepartmentDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(2)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                        <h4 class="level-2 rectangle">Team
                            <br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelTwo::getTeamDetails(2)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 40%; width: 40%; object-fit: contain'/>
                            <br>
                        </h4>

                        {{-- users--}}
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
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(3)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(3)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                        <h4 class="level-2 rectangle">Team
                            <br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelTwo::getTeamDetails(3)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 40%; width: 40%; object-fit: contain'/>
                            <br>
                        </h4>

                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(1) as $users)
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
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(1)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(1)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(1)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(1)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                        <h4 class="level-2 rectangle">Team
                            <br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(1)->name }}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(1)->manager->first_name .  ' ' .
                             \App\DivisionLevelTwo::getTeamDetails(1)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(1)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(1)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(1)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(1)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 40%; width: 40%; object-fit: contain'/>
                            <br>
                        </h4>

                        {{-- users--}}
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
                            {{ \App\DivisionLevelFour::getDepartmentDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(2)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                        <h4 class="level-2 rectangle">Team
                            <br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->name }}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->manager->first_name .  ' ' .
                             \App\DivisionLevelTwo::getTeamDetails(2)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(2)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(2)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(2)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(2)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 40%; width: 40%; object-fit: contain'/>
                            <br>
                        </h4>

                        {{-- users--}}
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
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelFour::getDepartmentDetails(3)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelFour::getDepartmentDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelFour::getDepartmentDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelFour::getDepartmentDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Section--}}
                        <h4 class="level-2 rectangle pfoto">
                            Section
                            <br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelThree::getSectionDetails(3)->manager->surname}}

                            <br><br>
                            {{ \App\DivisionLevelThree::getSectionDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelThree::getSectionDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelThree::getSectionDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 60%; width: 60%; object-fit: contain'/>
                            <br>
                        </h4>
                        {{-- Team--}}
                        <h4 class="level-2 rectangle">Team
                            <br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->name }}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->manager->first_name .  ' ' .
                             \App\DivisionLevelTwo::getTeamDetails(3)->manager->surname}}
                            <br><br>
                            {{ \App\DivisionLevelTwo::getTeamDetails(3)->hrRoles->description }}
                            <br><br>
                            <img src="{{ (!empty(\App\DivisionLevelTwo::getTeamDetails(3)->manager->profile_pic))
                                  ? asset('storage/avatars/'.\App\DivisionLevelTwo::getTeamDetails(3)->manager->profile_pic)  :
                                   ((\App\DivisionLevelTwo::getTeamDetails(3)->manager->gender === 0) ?
                                    $f_silhouette : $m_silhouette)}} "
                                 class="card-img-top" alt="Profile"
                                 style='height: 40%; width: 40%; object-fit: contain'/>
                            <br>
                        </h4>

                        {{-- users--}}
                        @foreach(\App\HRPerson::getUsersFromTeam(1) as $users)
                            <ol class="level-4-wrapper">
                                <li>
                                    <h4 class="level-4 rectangle">{{ $users->first_name . '' . $users->surname }}</h4>
                                </li>
                            </ol>
                        @endforeach
                    </li>

                </ol>
            </li>


        </ol>
    </div>
@stop
