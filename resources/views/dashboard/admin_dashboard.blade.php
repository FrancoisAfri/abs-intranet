@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@endsection
@section('content')
    {{----}}
    {{----}}
    @if($activeModules->where('code_name', 'cms')->first())
        <div class="row">
            <div class="col-md-6">
                <div class="box box-muted same-height-widget">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"></i>
                        <h3 class="box-title"> Company News</h3>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                        </ol>
                        <div class="carousel-inner">
                            @if (!empty($news))
                                @foreach($news as $key => $Cmsnews)

                                    <div class="item{{ $key == 0 ? ' active' : '' }}"> <!-- item 1 -->
                                        <a href="{{ '/view/' . $Cmsnews->id }}" id="edit_compan"
                                           class="btn btn-default  btn-xs" target="_blank"><i class=""></i> Read more
                                        </a>
                                        <img src="{{ Storage::disk('local')->url("CMS/images/$Cmsnews->image") }}" width="400" height="400">

                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                @include('dashboard.partials.view_news_modal')
            </div>

            <div class="col-md-6">
                <div class="box box-muted same-height-widget">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"></i>
                        <h3 class="box-title"> Company CEO Message</h3>
                    </div>
                    <div class="container">
                        <!--  <div class="page-header">
                             <h1 id="timeline">Timeline</h1>
                         </div> -->
                        @if (!empty($ceonews))
                            <ul class="timeline">
                                <li>
                                    <div class="timeline-badge"><i class="glyphicon glyphicon-check"></i></div>
                                    <div class="timeline-panel" style="max-height: 300px; max-width: 450px; overflow-y: scroll; overflow-x: scroll;">
                                        {{--<div class="no-padding" style="max-height: 220px; overflow-y: scroll;">--}}
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">
												<p style="padding:0 15 px; float: left"><img src="{{ Storage::disk('local')->url("CMS/images/$ceonews->image") }}" width="250" height="220"></p>
                                                <p style="margin-top:20px;"><N>{{!empty($ceonews->name) ? $ceonews->name : ''}}</N></p>
                                            </h4>
                                            <p>
                                                <small class="text-muted"><i
                                                            class="glyphicon glyphicon-time"></i> {{!empty($ceonews->date) ? date(' d M Y', $ceonews->date) : ''}}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="timeline-body">
                                            <p><font size="3">{!!$ceonews->summary!!}.</font></p>
                                            <div>
                                        </div>
                                        <hr>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{----}}
    {{--<div class="row">--}}
        {{--<div class="col-md-6">--}}
            {{--<!-- Employee Monthly performance Widget-->--}}
            {{--<div class="box box-primary">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Employee Monthly Appraisal</h3>--}}

                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
                                    {{--class="fa fa-minus"></i>--}}
                        {{--</button>--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i--}}
                                    {{--class="fa fa-times"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="wpb_wrapper">--}}

                    {{--<div class="wpb_text_column wpb_content_element ">--}}

                        {{--<div class="wpb_wrapper">--}}

                            {{--<h2>Latest at VUT</h2>--}}
                            {{--<div class="evcal_month_line">--}}
                                {{--<p><span style="font-family: helvetica, sans-serif; color: #4990c4;">FOLLOW US ON FACEBOOK</span>--}}
                                {{--</p>--}}
                            {{--</div>--}}


                        {{--</div>--}}

                    {{--</div>--}}


                    {{--<div class="box-body no-padding" style="max-height: 180px; overflow-y: scroll;">--}}

                        {{--<div class="wpb_wrapper">--}}

                            {{--<div class="cff-wrapper">--}}
                                {{--<div id="cff" data-char="" class="cff-fixed-height cff-default-styles"--}}
                                     {{--style="height:397px; ">--}}
                                    {{--<div class="cff-item cff-photo-post author-vaal-university-of-technology"--}}
                                         {{--id="cff_1284881071578767_1698573616876175"--}}
                                         {{--style="border-bottom: 0px solid #fff; -webkit-border-radius: px; -moz-border-radius: px; border-radius: px; ">--}}
                                        {{--<div class="cff-author"><a href="https://facebook.com/1284881071578767"--}}
                                                                   {{--target="_blank" rel="nofollow"--}}
                                                                   {{--title="Vaal University of Technology on Facebook">--}}
                                                {{--<div class="cff-author-text"><p class="cff-page-name cff-author-date">--}}
                                                        {{--Vaal University of Technology</p>--}}
                                                    {{--<p class="cff-date"> 2 days ago </p></div>--}}
                                                {{--<div class="cff-author-img"><img--}}
                                                            {{--src="https://graph.facebook.com/1284881071578767/picture?type=square"--}}
                                                            {{--title="Vaal University of Technology"--}}
                                                            {{--alt="Vaal University of Technology" width="40" height="40">--}}
                                                {{--</div>--}}
                                            {{--</a></div>--}}
                                        {{--<p class="cff-post-text"><span class="cff-text" data-color="">Forming partnerships that will benefit society<br> <br> Photo: Representatives from VUT and WRC<br> <br> On 13 February, the Vaal University of Technology Southern Gauteng Science and Technology Park had a visit from The Water Research Commission’s Technology Transfer Office (WRC TTO).<br> <br> The purpose of the meeting was to engage with VUT’s TTO to gain insight into the water projects in its portfolio and to understand the challenges faced in transferring technologies and related IP issues for water innovations. Some key issues discussed during the meeting included water-related projects funded (or not) by the WRC and the development, IP protection and commercialisation statuses thereof.<br> <br> The WRC identified the primary challenges to the global economy in 2018 as the water crises, the failure of climate change mitigation and adaptation as well as large-scale, involuntary migration. The WRC has five research priorities of which one is to solve water and water-related problems that are critical to South Africa’s sustainable development and economic growth. All of these priorities are grounded in solutions.<br> <br> In carrying out its mandate, the WRC TTO recognises the importance of building partnerships with technology transfer capabilities of institutions undertaking water-related research and development. The TTO wishes, therefore, to acquaint itself with the various projects carried out by different institutions and to assist them accelerate the commercialisation of water technologies and innovations.<br> <br> VUT’S response to the WRC is that it appreciates that the current water crisis in the Western Cape might be the precursor of a tragedy across South Africa, should anything happen to the water that the whole of South Africa gets from Lesotho and Mpumalanga. Thus, VUT will endeavour to motivate early-stage research from researchers of all disciplines to use the TIA Seed Fund calls to conduct research towards water solutions.<br> <br> Selina Rapulane<br> <br> <a--}}
                                                        {{--href="http://facebook.com/1708760889351213" style="color: #;"--}}
                                                        {{--target="_blank" rel="nofollow">VUT Southern Gauteng Science and Technology Park</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/VUTnews" target="_blank"--}}
                                                        {{--rel="nofollow" style="color:#">#VUTnews</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/ProudlyVUT"--}}
                                                        {{--target="_blank" rel="nofollow"--}}
                                                        {{--style="color:#">#ProudlyVUT</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/VUTSciencePark"--}}
                                                        {{--target="_blank" rel="nofollow"--}}
                                                        {{--style="color:#">#VUTSciencePark</a> <a--}}
                                                        {{--href="http://facebook.com/1599199350146922" style="color: #;"--}}
                                                        {{--target="_blank"--}}
                                                        {{--rel="nofollow">Heinrich van der Merwe</a> </span><span--}}
                                                    {{--class="cff-expand">... <a href="#" style="color: #"><span--}}
                                                            {{--class="cff-more"></span><span--}}
                                                            {{--class="cff-less"></span></a></span></p>--}}
                                        {{--<p class="cff-media-link"><a--}}
                                                    {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698573206876216/?type=3"--}}
                                                    {{--target="_blank" style="color: #;"><i style="padding-right: 5px;"--}}
                                                                                         {{--class="fa fa-picture-o"></i>Photo</a>--}}
                                        {{--</p>--}}
                                        {{--<div class="cff-post-links"><a class="cff-viewpost-facebook"--}}
                                                                       {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698573206876216/?type=3"--}}
                                                                       {{--title="View on Facebook" target="_blank"--}}
                                                                       {{--rel="nofollow">View on Facebook</a>--}}
                                            {{--<div class="cff-share-container"><span class="cff-dot">·</span><a--}}
                                                        {{--class="cff-share-link" href="javascript:void(0);" title="Share">Share</a>--}}
                                                {{--<p class="cff-share-tooltip"><a--}}
                                                            {{--href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698573206876216%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-facebook-icon"><i--}}
                                                                {{--class="fa fa-facebook-square"></i></a><a--}}
                                                            {{--href="https://twitter.com/intent/tweet?text=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698573206876216%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-twitter-icon"><i--}}
                                                                {{--class="fa fa-twitter"></i></a><a--}}
                                                            {{--href="https://plus.google.com/share?url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698573206876216%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-google-icon"><i--}}
                                                                {{--class="fa fa-google-plus"></i></a><a--}}
                                                            {{--href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698573206876216%2F%3Ftype%3D3&amp;title=Forming%20partnerships%20that%20will%20benefit%20societyPhoto%3A%20Representatives%20from%20VUT%20and%20WRCOn%2013%20February%2C%20the%20Vaal%20University%20of%20Technology%20Southern%20Gauteng%20Science%20and%20Technology%20Park%20had%20a%20visit%20from%20The%20Water%20Research%20Commission%E2%80%99s%20Technology%20Transfer%20Office%20%28WRC%20TTO%29.The%20purpose%20of%20the%20meeting%20was%20to%20engage%20with%20VUT%E2%80%99s%20TTO%20to%20gain%20insight%20into%20the%20water%20projects%20in%20its%20portfolio%20and%20to%20understand%20the%20challenges%20faced%20in%20transferring%20technologies%20and%20related%20IP%20issues%20for%20water%20innovations.%20Some%20key%20issues%20discussed%20during%20the%20meeting%20included%20water-related%20projects%20funded%20%28or%20not%29%20by%20the%20WRC%20and%20the%20development%2C%20IP%20protection%20and%20commercialisation%20statuses%20thereof.The%20WRC%20identified%20the%20primary%20challenges%20to%20the%20global%20economy%20in%202018%20as%20the%20water%20crises%2C%20the%20failure%20of%20climate%20change%20mitigation%20and%20adaptation%20as%20well%20as%20large-scale%2C%20involuntary%20migration.%20The%20WRC%20has%20five%20research%20priorities%20of%20which%20one%20is%20to%20solve%20water%20and%20water-related%20problems%20that%20are%20critical%20to%20South%20Africa%E2%80%99s%20sustainable%20development%20and%20economic%20growth.%20All%20of%20these%20priorities%20are%20grounded%20in%20solutions.In%20carrying%20out%20its%20mandate%2C%20the%20WRC%20TTO%20recognises%20the%20importance%20of%20building%20partnerships%20with%20technology%20transfer%20capabilities%20of%20institutions%20undertaking%20water-related%20research%20and%20development.%20The%20TTO%20wishes%2C%20therefore%2C%20to%20acquaint%20itself%20with%20the%20various%20projects%20carried%20out%20by%20different%20institutions%20and%20to%20assist%20them%20accelerate%20the%20commercialisation%20of%20water%20technologies%20and%20innovations.VUT%E2%80%99S%20response%20to%20the%20WRC%20is%20that%20it%20appreciates%20that%20the%20current%20water%20crisis%20in%20the%20Western%20Cape%20might%20be%20the%20precursor%20of%20a%20tragedy%20across%20South%20Africa%2C%20should%20anything%20happen%20to%20the%20water%20that%20the%20whole%20of%20South%20Africa%20gets%20from%20Lesotho%20and%20Mpumalanga.%20Thus%2C%20VUT%20will%20endeavour%20to%20motivate%20early-stage%20research%20from%20researchers%20of%20all%20disciplines%20to%20use%20the%20TIA%20Seed%20Fund%20calls%20to%20conduct%20research%20towards%20water%20solutions.Selina%20RapulaneVUT%20Southern%20Gauteng%20Science%20and%20Technology%20Park%20%23VUTnews%20%23ProudlyVUT%20%23VUTSciencePark%20Heinrich%20van%20der%20Merwe%20...%20"--}}
                                                            {{--target="_blank" class="cff-linkedin-icon"><i--}}
                                                                {{--class="fa fa-linkedin"></i></a><a--}}
                                                            {{--href="mailto:?subject=Facebook&amp;body=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698573206876216%2F%3Ftype%3D3%20-%20Forming%20partnerships%20that%20will%20benefit%20societyPhoto%3A%20Representatives%20from%20VUT%20and%20WRCOn%2013%20February%2C%20the%20Vaal%20University%20of%20Technology%20Southern%20Gauteng%20Science%20and%20Technology%20Park%20had%20a%20visit%20from%20The%20Water%20Research%20Commission%E2%80%99s%20Technology%20Transfer%20Office%20%28WRC%20TTO%29.The%20purpose%20of%20the%20meeting%20was%20to%20engage%20with%20VUT%E2%80%99s%20TTO%20to%20gain%20insight%20into%20the%20water%20projects%20in%20its%20portfolio%20and%20to%20understand%20the%20challenges%20faced%20in%20transferring%20technologies%20and%20related%20IP%20issues%20for%20water%20innovations.%20Some%20key%20issues%20discussed%20during%20the%20meeting%20included%20water-related%20projects%20funded%20%28or%20not%29%20by%20the%20WRC%20and%20the%20development%2C%20IP%20protection%20and%20commercialisation%20statuses%20thereof.The%20WRC%20identified%20the%20primary%20challenges%20to%20the%20global%20economy%20in%202018%20as%20the%20water%20crises%2C%20the%20failure%20of%20climate%20change%20mitigation%20and%20adaptation%20as%20well%20as%20large-scale%2C%20involuntary%20migration.%20The%20WRC%20has%20five%20research%20priorities%20of%20which%20one%20is%20to%20solve%20water%20and%20water-related%20problems%20that%20are%20critical%20to%20South%20Africa%E2%80%99s%20sustainable%20development%20and%20economic%20growth.%20All%20of%20these%20priorities%20are%20grounded%20in%20solutions.In%20carrying%20out%20its%20mandate%2C%20the%20WRC%20TTO%20recognises%20the%20importance%20of%20building%20partnerships%20with%20technology%20transfer%20capabilities%20of%20institutions%20undertaking%20water-related%20research%20and%20development.%20The%20TTO%20wishes%2C%20therefore%2C%20to%20acquaint%20itself%20with%20the%20various%20projects%20carried%20out%20by%20different%20institutions%20and%20to%20assist%20them%20accelerate%20the%20commercialisation%20of%20water%20technologies%20and%20innovations.VUT%E2%80%99S%20response%20to%20the%20WRC%20is%20that%20it%20appreciates%20that%20the%20current%20water%20crisis%20in%20the%20Western%20Cape%20might%20be%20the%20precursor%20of%20a%20tragedy%20across%20South%20Africa%2C%20should%20anything%20happen%20to%20the%20water%20that%20the%20whole%20of%20South%20Africa%20gets%20from%20Lesotho%20and%20Mpumalanga.%20Thus%2C%20VUT%20will%20endeavour%20to%20motivate%20early-stage%20research%20from%20researchers%20of%20all%20disciplines%20to%20use%20the%20TIA%20Seed%20Fund%20calls%20to%20conduct%20research%20towards%20water%20solutions.Selina%20RapulaneVUT%20Southern%20Gauteng%20Science%20and%20Technology%20Park%20%23VUTnews%20%23ProudlyVUT%20%23VUTSciencePark%20Heinrich%20van%20der%20Merwe%20...%20"--}}
                                                            {{--target="_blank" class="cff-email-icon"><i--}}
                                                                {{--class="fa fa-envelope"></i></a><i--}}
                                                            {{--class="fa fa-play fa-rotate-90"></i></p></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="cff-item cff-photo-post author-vaal-university-of-technology"--}}
                                         {{--id="cff_1284881071578767_1698572160209654"--}}
                                         {{--style="border-bottom: 0px solid #fff; -webkit-border-radius: px; -moz-border-radius: px; border-radius: px; ">--}}
                                        {{--<div class="cff-author"><a href="https://facebook.com/1284881071578767"--}}
                                                                   {{--target="_blank" rel="nofollow"--}}
                                                                   {{--title="Vaal University of Technology on Facebook">--}}
                                                {{--<div class="cff-author-text"><p class="cff-page-name cff-author-date">--}}
                                                        {{--Vaal University of Technology</p>--}}
                                                    {{--<p class="cff-date"> 2 days ago </p></div>--}}
                                                {{--<div class="cff-author-img"><img--}}
                                                            {{--src="https://graph.facebook.com/1284881071578767/picture?type=square"--}}
                                                            {{--title="Vaal University of Technology"--}}
                                                            {{--alt="Vaal University of Technology" width="40" height="40">--}}
                                                {{--</div>--}}
                                            {{--</a></div>--}}
                                        {{--<p class="cff-post-text"><span class="cff-text" data-color="">“We are in business”<br> <br> Photo: Mr Paseka Mokoena with judges and adjudicators at the Sedibeng <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/PitchingBooster"--}}
                                                        {{--target="_blank" rel="nofollow"--}}
                                                        {{--style="color:#">#PitchingBooster</a> campaign fueled by Gauteng Enterprise Propeller<br> <br> On 14 February 2018, Mr Paseka Mokoena, a VUT-Sasol Entrepreneurship Programme Cohort 7 Alumnus scooped funding for his company at The Gauteng Enterprise Propeller (GEP) Pitching Booster held at Emerald Casino, VanderbijlPark.<br> <br> After graduating from the VUT-Sasol Entrepreneurship Programme on 13 October 2017, Mr Mokoena (23), Mr Refiloe Mpelo (25) and Ms Nompumelelo Hoboyi (19) attended the Start-Up weekend Programme held from  27-29 October 2017 at the Sebokeng Ekasi Lab.  There they won a nine months incubation for their project. They then decided to start their own tech company called Afrinnovix.<br> <br> After seeing the GEP competition advertised on Facebook, the trio decided to apply and their application was successful. Mr Mokoena attended on behalf of the company. The event was held over two days: advice on business and pitching was offered on the first day while the second day was for the actual pitching.<br> <br> The GEP Pitching Booster invited 150 SMMES and 50 unemployed youth from Sedibeng to pitch their businesses or business ideas for a funding opportunity.<br> <br> Afrinnovix was successful and the company is thrilled.<br> <br> “We benefitted from everything we learnt through the VUT-Sasol entrepreneurship programme and we feel proud that our hard work and determination is paying off even though we still have a long way to go,” said Mr Mokoena.<br> <br> The three are determined to grow their company and offer technologies that will make life easier for society. They are using the Science Park’s Fab-Lab facilities for most of their experiments and 3D printing machines for prototypes. They are still incubated at the lab and are working on their prototype which is a backup power phone pouch – a smartphone accessory.<br> <br> GEP promotes, fosters and develops small enterprises in Gauteng thereby implementing the policy of the Gauteng Provincial Government for small enterprise development.<br> <br> Selina Rapulane<br> <br> <a--}}
                                                        {{--href="http://facebook.com/1708760889351213" style="color: #;"--}}
                                                        {{--target="_blank" rel="nofollow">VUT Southern Gauteng Science and Technology Park</a><br> <a--}}
                                                        {{--href="http://facebook.com/1306115802854809" style="color: #;"--}}
                                                        {{--target="_blank" rel="nofollow">Mpho Selina Rapulane</a><br> <br> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/VUTNews" target="_blank"--}}
                                                        {{--rel="nofollow" style="color:#">#VUTNews</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/ProudlyVUT"--}}
                                                        {{--target="_blank" rel="nofollow"--}}
                                                        {{--style="color:#">#ProudlyVUT</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/VUTSciencePark"--}}
                                                        {{--target="_blank" rel="nofollow"--}}
                                                        {{--style="color:#">#VUTSciencePark</a> </span><span--}}
                                                    {{--class="cff-expand">... <a href="#" style="color: #"><span--}}
                                                            {{--class="cff-more"></span><span--}}
                                                            {{--class="cff-less"></span></a></span></p>--}}
                                        {{--<p class="cff-media-link"><a--}}
                                                    {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698572350209635/?type=3"--}}
                                                    {{--target="_blank" style="color: #;"><i style="padding-right: 5px;"--}}
                                                                                         {{--class="fa fa-picture-o"></i>Photo</a>--}}
                                        {{--</p>--}}
                                        {{--<div class="cff-post-links"><a class="cff-viewpost-facebook"--}}
                                                                       {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698572350209635/?type=3"--}}
                                                                       {{--title="View on Facebook" target="_blank"--}}
                                                                       {{--rel="nofollow">View on Facebook</a>--}}
                                            {{--<div class="cff-share-container"><span class="cff-dot">·</span><a--}}
                                                        {{--class="cff-share-link" href="javascript:void(0);" title="Share">Share</a>--}}
                                                {{--<p class="cff-share-tooltip"><a--}}
                                                            {{--href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698572350209635%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-facebook-icon"><i--}}
                                                                {{--class="fa fa-facebook-square"></i></a><a--}}
                                                            {{--href="https://twitter.com/intent/tweet?text=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698572350209635%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-twitter-icon"><i--}}
                                                                {{--class="fa fa-twitter"></i></a><a--}}
                                                            {{--href="https://plus.google.com/share?url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698572350209635%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-google-icon"><i--}}
                                                                {{--class="fa fa-google-plus"></i></a><a--}}
                                                            {{--href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698572350209635%2F%3Ftype%3D3&amp;title=%E2%80%9CWe%20are%20in%20business%E2%80%9DPhoto%3A%20Mr%20Paseka%20Mokoena%20with%20judges%20and%20adjudicators%20at%20the%20Sedibeng%20%23PitchingBooster%20campaign%20fueled%20by%20Gauteng%20Enterprise%20PropellerOn%2014%20February%202018%2C%20Mr%20Paseka%20Mokoena%2C%20a%20VUT-Sasol%20Entrepreneurship%20Programme%20Cohort%207%20Alumnus%20scooped%20funding%20for%20his%20company%20at%20The%20Gauteng%20Enterprise%20Propeller%20%28GEP%29%20Pitching%20Booster%20held%20at%20Emerald%20Casino%2C%20VanderbijlPark.After%20graduating%20from%20the%20VUT-Sasol%20Entrepreneurship%20Programme%20on%2013%20October%202017%2C%20Mr%20Mokoena%20%2823%29%2C%20Mr%20Refiloe%20Mpelo%20%2825%29%20and%20Ms%20Nompumelelo%20Hoboyi%20%2819%29%20attended%20the%20Start-Up%20weekend%20Programme%20held%20from%20%2027-29%20October%202017%20at%20the%20Sebokeng%20Ekasi%20Lab.%20%20There%20they%20won%20a%20nine%20months%20incubation%20for%20their%20project.%20They%20then%20decided%20to%20start%20their%20own%20tech%20company%20called%20Afrinnovix.After%20seeing%20the%20GEP%20competition%20advertised%20on%20Facebook%2C%20the%20trio%20decided%20to%20apply%20and%20their%20application%20was%20successful.%20Mr%20Mokoena%20attended%20on%20behalf%20of%20the%20company.%20The%20event%20was%20held%20over%20two%20days%3A%20advice%20on%20business%20and%20pitching%20was%20offered%20on%20the%20first%20day%20while%20the%20second%20day%20was%20for%20the%20actual%20pitching.The%20GEP%20Pitching%20Booster%20invited%20150%20SMMES%20and%2050%20unemployed%20youth%20from%20Sedibeng%20to%20pitch%20their%20businesses%20or%20business%20ideas%20for%20a%20funding%20opportunity.Afrinnovix%20was%20successful%20and%20the%20company%20is%20thrilled.%E2%80%9CWe%20benefitted%20from%20everything%20we%20learnt%20through%20the%20VUT-Sasol%20entrepreneurship%20programme%20and%20we%20feel%20proud%20that%20our%20hard%20work%20and%20determination%20is%20paying%20off%20even%20though%20we%20still%20have%20a%20long%20way%20to%20go%2C%E2%80%9D%20said%20Mr%20Mokoena.The%20three%20are%20determined%20to%20grow%20their%20company%20and%20offer%20technologies%20that%20will%20make%20life%20easier%20for%20society.%20They%20are%20using%20the%20Science%20Park%E2%80%99s%20Fab-Lab%20facilities%20for%20most%20of%20their%20experiments%20and%203D%20printing%20machines%20for%20prototypes.%20They%20are%20still%20incubated%20at%20the%20lab%20and%20are%20working%20on%20their%20prototype%20which%20is%20a%20backup%20power%20phone%20pouch%20%E2%80%93%20a%20smartphone%20accessory.GEP%20promotes%2C%20fosters%20and%20develops%20small%20enterprises%20in%20Gauteng%20thereby%20implementing%20the%20policy%20of%20the%20Gauteng%20Provincial%20Government%20for%20small%20enterprise%20development.Selina%20RapulaneVUT%20Southern%20Gauteng%20Science%20and%20Technology%20ParkMpho%20Selina%20Rapulane%23VUTNews%20%23ProudlyVUT%20%23VUTSciencePark%20...%20"--}}
                                                            {{--target="_blank" class="cff-linkedin-icon"><i--}}
                                                                {{--class="fa fa-linkedin"></i></a><a--}}
                                                            {{--href="mailto:?subject=Facebook&amp;body=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698572350209635%2F%3Ftype%3D3%20-%20%E2%80%9CWe%20are%20in%20business%E2%80%9DPhoto%3A%20Mr%20Paseka%20Mokoena%20with%20judges%20and%20adjudicators%20at%20the%20Sedibeng%20%23PitchingBooster%20campaign%20fueled%20by%20Gauteng%20Enterprise%20PropellerOn%2014%20February%202018%2C%20Mr%20Paseka%20Mokoena%2C%20a%20VUT-Sasol%20Entrepreneurship%20Programme%20Cohort%207%20Alumnus%20scooped%20funding%20for%20his%20company%20at%20The%20Gauteng%20Enterprise%20Propeller%20%28GEP%29%20Pitching%20Booster%20held%20at%20Emerald%20Casino%2C%20VanderbijlPark.After%20graduating%20from%20the%20VUT-Sasol%20Entrepreneurship%20Programme%20on%2013%20October%202017%2C%20Mr%20Mokoena%20%2823%29%2C%20Mr%20Refiloe%20Mpelo%20%2825%29%20and%20Ms%20Nompumelelo%20Hoboyi%20%2819%29%20attended%20the%20Start-Up%20weekend%20Programme%20held%20from%20%2027-29%20October%202017%20at%20the%20Sebokeng%20Ekasi%20Lab.%20%20There%20they%20won%20a%20nine%20months%20incubation%20for%20their%20project.%20They%20then%20decided%20to%20start%20their%20own%20tech%20company%20called%20Afrinnovix.After%20seeing%20the%20GEP%20competition%20advertised%20on%20Facebook%2C%20the%20trio%20decided%20to%20apply%20and%20their%20application%20was%20successful.%20Mr%20Mokoena%20attended%20on%20behalf%20of%20the%20company.%20The%20event%20was%20held%20over%20two%20days%3A%20advice%20on%20business%20and%20pitching%20was%20offered%20on%20the%20first%20day%20while%20the%20second%20day%20was%20for%20the%20actual%20pitching.The%20GEP%20Pitching%20Booster%20invited%20150%20SMMES%20and%2050%20unemployed%20youth%20from%20Sedibeng%20to%20pitch%20their%20businesses%20or%20business%20ideas%20for%20a%20funding%20opportunity.Afrinnovix%20was%20successful%20and%20the%20company%20is%20thrilled.%E2%80%9CWe%20benefitted%20from%20everything%20we%20learnt%20through%20the%20VUT-Sasol%20entrepreneurship%20programme%20and%20we%20feel%20proud%20that%20our%20hard%20work%20and%20determination%20is%20paying%20off%20even%20though%20we%20still%20have%20a%20long%20way%20to%20go%2C%E2%80%9D%20said%20Mr%20Mokoena.The%20three%20are%20determined%20to%20grow%20their%20company%20and%20offer%20technologies%20that%20will%20make%20life%20easier%20for%20society.%20They%20are%20using%20the%20Science%20Park%E2%80%99s%20Fab-Lab%20facilities%20for%20most%20of%20their%20experiments%20and%203D%20printing%20machines%20for%20prototypes.%20They%20are%20still%20incubated%20at%20the%20lab%20and%20are%20working%20on%20their%20prototype%20which%20is%20a%20backup%20power%20phone%20pouch%20%E2%80%93%20a%20smartphone%20accessory.GEP%20promotes%2C%20fosters%20and%20develops%20small%20enterprises%20in%20Gauteng%20thereby%20implementing%20the%20policy%20of%20the%20Gauteng%20Provincial%20Government%20for%20small%20enterprise%20development.Selina%20RapulaneVUT%20Southern%20Gauteng%20Science%20and%20Technology%20ParkMpho%20Selina%20Rapulane%23VUTNews%20%23ProudlyVUT%20%23VUTSciencePark%20...%20"--}}
                                                            {{--target="_blank" class="cff-email-icon"><i--}}
                                                                {{--class="fa fa-envelope"></i></a><i--}}
                                                            {{--class="fa fa-play fa-rotate-90"></i></p></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="cff-item cff-photo-post author-vaal-university-of-technology"--}}
                                         {{--id="cff_1284881071578767_1698570856876451"--}}
                                         {{--style="border-bottom: 0px solid #fff; -webkit-border-radius: px; -moz-border-radius: px; border-radius: px; ">--}}
                                        {{--<div class="cff-author"><a href="https://facebook.com/1284881071578767"--}}
                                                                   {{--target="_blank" rel="nofollow"--}}
                                                                   {{--title="Vaal University of Technology on Facebook">--}}
                                                {{--<div class="cff-author-text"><p class="cff-page-name cff-author-date">--}}
                                                        {{--Vaal University of Technology</p>--}}
                                                    {{--<p class="cff-date"> 2 days ago </p></div>--}}
                                                {{--<div class="cff-author-img"><img--}}
                                                            {{--src="https://graph.facebook.com/1284881071578767/picture?type=square"--}}
                                                            {{--title="Vaal University of Technology"--}}
                                                            {{--alt="Vaal University of Technology" width="40" height="40">--}}
                                                {{--</div>--}}
                                            {{--</a></div>--}}
                                        {{--<p class="cff-post-text"><span class="cff-text" data-color="">VUT Co-operative Education enhances the employability of students and graduates<br> <br> Photo: Programme facilitator Mr Fusi Motaung<br> <br> VUT Co-operative Education Department held employability workshops from 13 -15 February 2018 at the Desmond Tutu Great Hall. The workshops form part of the Department’s activities. The workshops prepared Vaal University of Technology students and graduates on how to cope in the job market. It also equipped them with skills to search for jobs effectively.<br> <br> The workshops, which was open to all students, was designed to enable students to acquire knowledge, personal and professional skills and encourage the attitudes that will support their future development and employment.<br> <br> The aim of the workshops was to develop students and graduates’ understanding of the complexities surrounding employability. The insightful programme was facilitated by Mr. Fusi Motaung, who is an experienced training professional. A former Human Resources Management student of VUT, he is currently the training and talent consultant of Morolo Consultants.<br> <br> Mr. Motaung introduced students to a standard set of three skills for effective employability. Throughout this programme, these three skills were emphasised for students and graduates to further develop. They are as follows:<br> <br> Curriculum Vitae writing skills;<br> Job-searching skills; and<br> Interview skills.<br> These are the skills which are generally found on lists of unemployed graduates’ most desired skills.<br> <br> It has been proven that a CV is the most important tool used to communicate in the job market: “A CV is your personal billboard, it allows you to sell your potential to employers and it also shows that you are suitably qualified,” Mr Motaung said. “There are vital points one needs to take serious consideration of when drafting a CV. Keep it clear and focus on success.”<br> <br> The speaker elaborated to the attendees that they need “the elevator pitch”. You should ask yourself questions like: “Who am I? What do I do? What do I want to do?” He said that mastering one’s CV is very important and that it should be constantly updated.<br> <br> The South African labour landscape reveals that there is a 27.70% unemployment rate and a 55.90% youth unemployment rate. The problems revealed by Mr Motaung were that graduates do not have professional networks, they lack job market information as well as confidence. They do not trust themselves to speak out and promote themselves.<br> <br> In job searching, it is important to have personal contact with individuals and industry.<br> <br> “Involve yourself in one-on-one approaches, interest groups and social media. This will assist you to pull out information and to know exactly who you are dealing with, therefore you will be prepared effectively,” he said.<br> <br> Preparation is vital to perform successfully in an interview. The speaker showed the attendees how to do research, what to expect and he also took them through common competency-based questions.<br> <br> Tebello Theledi<br> <br> <a--}}
                                                        {{--href="http://facebook.com/1775098062560615" style="color: #;"--}}
                                                        {{--target="_blank" rel="nofollow">Vut Co-op</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/VUTNews" target="_blank"--}}
                                                        {{--rel="nofollow" style="color:#">#VUTNews</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/Skills" target="_blank"--}}
                                                        {{--rel="nofollow" style="color:#">#Skills</a> <a--}}
                                                        {{--href="https://www.facebook.com/hashtag/workshop" target="_blank"--}}
                                                        {{--rel="nofollow" style="color:#">#workshop</a> </span><span--}}
                                                    {{--class="cff-expand">... <a href="#" style="color: #"><span--}}
                                                            {{--class="cff-more"></span><span--}}
                                                            {{--class="cff-less"></span></a></span></p>--}}
                                        {{--<p class="cff-media-link"><a--}}
                                                    {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698571086876428/?type=3"--}}
                                                    {{--target="_blank" style="color: #;"><i style="padding-right: 5px;"--}}
                                                                                         {{--class="fa fa-picture-o"></i>Photo</a>--}}
                                        {{--</p>--}}
                                        {{--<div class="cff-post-links"><a class="cff-viewpost-facebook"--}}
                                                                       {{--href="https://www.facebook.com/maVUTi/photos/a.1285874694812738.1073741829.1284881071578767/1698571086876428/?type=3"--}}
                                                                       {{--title="View on Facebook" target="_blank"--}}
                                                                       {{--rel="nofollow">View on Facebook</a>--}}
                                            {{--<div class="cff-share-container"><span class="cff-dot">·</span><a--}}
                                                        {{--class="cff-share-link" href="javascript:void(0);" title="Share">Share</a>--}}
                                                {{--<p class="cff-share-tooltip"><a--}}
                                                            {{--href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698571086876428%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-facebook-icon"><i--}}
                                                                {{--class="fa fa-facebook-square"></i></a><a--}}
                                                            {{--href="https://twitter.com/intent/tweet?text=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698571086876428%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-twitter-icon"><i--}}
                                                                {{--class="fa fa-twitter"></i></a><a--}}
                                                            {{--href="https://plus.google.com/share?url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698571086876428%2F%3Ftype%3D3"--}}
                                                            {{--target="_blank" class="cff-google-icon"><i--}}
                                                                {{--class="fa fa-google-plus"></i></a><a--}}
                                                            {{--href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698571086876428%2F%3Ftype%3D3&amp;title=VUT%20Co-operative%20Education%20enhances%20the%20employability%20of%20students%20and%20graduatesPhoto%3A%20Programme%20facilitator%20Mr%20Fusi%20MotaungVUT%20Co-operative%20Education%20Department%20held%20employability%20workshops%20from%2013%20-15%20February%202018%20at%20the%20Desmond%20Tutu%20Great%20Hall.%20The%20workshops%20form%20part%20of%20the%20Department%E2%80%99s%20activities.%20The%20workshops%20prepared%20Vaal%20University%20of%20Technology%20students%20and%20graduates%20on%20how%20to%20cope%20in%20the%20job%20market.%20It%20also%20equipped%20them%20with%20skills%20to%20search%20for%20jobs%20effectively.The%20workshops%2C%20which%20was%20open%20to%20all%20students%2C%20was%20designed%20to%20enable%20students%20to%20acquire%20knowledge%2C%20personal%20and%20professional%20skills%20and%20encourage%20the%20attitudes%20that%20will%20support%20their%20future%20development%20and%20employment.The%20aim%20of%20the%20workshops%20was%20to%20develop%20students%20and%20graduates%E2%80%99%20understanding%20of%20the%20complexities%20surrounding%20employability.%20The%20insightful%20programme%20was%20facilitated%20by%20Mr.%20Fusi%20Motaung%2C%20who%20is%20an%20experienced%20training%20professional.%20A%20former%20Human%20Resources%20Management%20student%20of%20VUT%2C%20he%20is%20currently%20the%20training%20and%20talent%20consultant%20of%20Morolo%20Consultants.Mr.%20Motaung%20introduced%20students%20to%20a%20standard%20set%20of%20three%20skills%20for%20effective%20employability.%20Throughout%20this%20programme%2C%20these%20three%20skills%20were%20emphasised%20for%20students%20and%20graduates%20to%20further%20develop.%20They%20are%20as%20follows%3ACurriculum%20Vitae%20writing%20skills%3BJob-searching%20skills%3B%20andInterview%20skills.These%20are%20the%20skills%20which%20are%20generally%20found%20on%20lists%20of%20unemployed%20graduates%E2%80%99%20most%20desired%20skills.It%20has%20been%20proven%20that%20a%20CV%20is%20the%20most%20important%20tool%20used%20to%20communicate%20in%20the%20job%20market%3A%20%E2%80%9CA%20CV%20is%20your%20personal%20billboard%2C%20it%20allows%20you%20to%20sell%20your%20potential%20to%20employers%20and%20it%20also%20shows%20that%20you%20are%20suitably%20qualified%2C%E2%80%9D%20Mr%20Motaung%20said.%20%E2%80%9CThere%20are%20vital%20points%20one%20needs%20to%20take%20serious%20consideration%20of%20when%20drafting%20a%20CV.%20Keep%20it%20clear%20and%20focus%20on%20success.%E2%80%9DThe%20speaker%20elaborated%20to%20the%20attendees%20that%20they%20need%20%E2%80%9Cthe%20elevator%20pitch%E2%80%9D.%20You%20should%20ask%20yourself%20questions%20like%3A%20%E2%80%9CWho%20am%20I%3F%20What%20do%20I%20do%3F%20What%20do%20I%20want%20to%20do%3F%E2%80%9D%20He%20said%20that%20mastering%20one%E2%80%99s%20CV%20is%20very%20important%20and%20that%20it%20should%20be%20constantly%20updated.The%20South%20African%20labour%20landscape%20reveals%20that%20there%20is%20a%2027.70%25%20unemployment%20rate%20and%20a%2055.90%25%20youth%20unemployment%20rate.%20The%20problems%20revealed%20by%20Mr%20Motaung%20were%20that%20graduates%20do%20not%20have%20professional%20networks%2C%20they%20lack%20job%20market%20information%20as%20well%20as%20confidence.%20They%20do%20not%20trust%20themselves%20to%20speak%20out%20and%20promote%20themselves.In%20job%20searching%2C%20it%20is%20important%20to%20have%20personal%20contact%20with%20individuals%20and%20industry.%E2%80%9CInvolve%20yourself%20in%20one-on-one%20approaches%2C%20interest%20groups%20and%20social%20media.%20This%20will%20assist%20you%20to%20pull%20out%20information%20and%20to%20know%20exactly%20who%20you%20are%20dealing%20with%2C%20therefore%20you%20will%20be%20prepared%20effectively%2C%E2%80%9D%20he%20said.Preparation%20is%20vital%20to%20perform%20successfully%20in%20an%20interview.%20The%20speaker%20showed%20the%20attendees%20how%20to%20do%20research%2C%20what%20to%20expect%20and%20he%20also%20took%20them%20through%20common%20competency-based%20questions.Tebello%20ThelediVut%20Co-op%20%23VUTNews%20%23Skills%20%23workshop%20...%20"--}}
                                                            {{--target="_blank" class="cff-linkedin-icon"><i--}}
                                                                {{--class="fa fa-linkedin"></i></a><a--}}
                                                            {{--href="mailto:?subject=Facebook&amp;body=https%3A%2F%2Fwww.facebook.com%2FmaVUTi%2Fphotos%2Fa.1285874694812738.1073741829.1284881071578767%2F1698571086876428%2F%3Ftype%3D3%20-%20VUT%20Co-operative%20Education%20enhances%20the%20employability%20of%20students%20and%20graduatesPhoto%3A%20Programme%20facilitator%20Mr%20Fusi%20MotaungVUT%20Co-operative%20Education%20Department%20held%20employability%20workshops%20from%2013%20-15%20February%202018%20at%20the%20Desmond%20Tutu%20Great%20Hall.%20The%20workshops%20form%20part%20of%20the%20Department%E2%80%99s%20activities.%20The%20workshops%20prepared%20Vaal%20University%20of%20Technology%20students%20and%20graduates%20on%20how%20to%20cope%20in%20the%20job%20market.%20It%20also%20equipped%20them%20with%20skills%20to%20search%20for%20jobs%20effectively.The%20workshops%2C%20which%20was%20open%20to%20all%20students%2C%20was%20designed%20to%20enable%20students%20to%20acquire%20knowledge%2C%20personal%20and%20professional%20skills%20and%20encourage%20the%20attitudes%20that%20will%20support%20their%20future%20development%20and%20employment.The%20aim%20of%20the%20workshops%20was%20to%20develop%20students%20and%20graduates%E2%80%99%20understanding%20of%20the%20complexities%20surrounding%20employability.%20The%20insightful%20programme%20was%20facilitated%20by%20Mr.%20Fusi%20Motaung%2C%20who%20is%20an%20experienced%20training%20professional.%20A%20former%20Human%20Resources%20Management%20student%20of%20VUT%2C%20he%20is%20currently%20the%20training%20and%20talent%20consultant%20of%20Morolo%20Consultants.Mr.%20Motaung%20introduced%20students%20to%20a%20standard%20set%20of%20three%20skills%20for%20effective%20employability.%20Throughout%20this%20programme%2C%20these%20three%20skills%20were%20emphasised%20for%20students%20and%20graduates%20to%20further%20develop.%20They%20are%20as%20follows%3ACurriculum%20Vitae%20writing%20skills%3BJob-searching%20skills%3B%20andInterview%20skills.These%20are%20the%20skills%20which%20are%20generally%20found%20on%20lists%20of%20unemployed%20graduates%E2%80%99%20most%20desired%20skills.It%20has%20been%20proven%20that%20a%20CV%20is%20the%20most%20important%20tool%20used%20to%20communicate%20in%20the%20job%20market%3A%20%E2%80%9CA%20CV%20is%20your%20personal%20billboard%2C%20it%20allows%20you%20to%20sell%20your%20potential%20to%20employers%20and%20it%20also%20shows%20that%20you%20are%20suitably%20qualified%2C%E2%80%9D%20Mr%20Motaung%20said.%20%E2%80%9CThere%20are%20vital%20points%20one%20needs%20to%20take%20serious%20consideration%20of%20when%20drafting%20a%20CV.%20Keep%20it%20clear%20and%20focus%20on%20success.%E2%80%9DThe%20speaker%20elaborated%20to%20the%20attendees%20that%20they%20need%20%E2%80%9Cthe%20elevator%20pitch%E2%80%9D.%20You%20should%20ask%20yourself%20questions%20like%3A%20%E2%80%9CWho%20am%20I%3F%20What%20do%20I%20do%3F%20What%20do%20I%20want%20to%20do%3F%E2%80%9D%20He%20said%20that%20mastering%20one%E2%80%99s%20CV%20is%20very%20important%20and%20that%20it%20should%20be%20constantly%20updated.The%20South%20African%20labour%20landscape%20reveals%20that%20there%20is%20a%2027.70%25%20unemployment%20rate%20and%20a%2055.90%25%20youth%20unemployment%20rate.%20The%20problems%20revealed%20by%20Mr%20Motaung%20were%20that%20graduates%20do%20not%20have%20professional%20networks%2C%20they%20lack%20job%20market%20information%20as%20well%20as%20confidence.%20They%20do%20not%20trust%20themselves%20to%20speak%20out%20and%20promote%20themselves.In%20job%20searching%2C%20it%20is%20important%20to%20have%20personal%20contact%20with%20individuals%20and%20industry.%E2%80%9CInvolve%20yourself%20in%20one-on-one%20approaches%2C%20interest%20groups%20and%20social%20media.%20This%20will%20assist%20you%20to%20pull%20out%20information%20and%20to%20know%20exactly%20who%20you%20are%20dealing%20with%2C%20therefore%20you%20will%20be%20prepared%20effectively%2C%E2%80%9D%20he%20said.Preparation%20is%20vital%20to%20perform%20successfully%20in%20an%20interview.%20The%20speaker%20showed%20the%20attendees%20how%20to%20do%20research%2C%20what%20to%20expect%20and%20he%20also%20took%20them%20through%20common%20competency-based%20questions.Tebello%20ThelediVut%20Co-op%20%23VUTNews%20%23Skills%20%23workshop%20...%20"--}}
                                                            {{--target="_blank" class="cff-email-icon"><i--}}
                                                                {{--class="fa fa-envelope"></i></a><i--}}
                                                            {{--class="fa fa-play fa-rotate-90"></i></p></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="cff-clear"></div>--}}
                            {{--</div>--}}


                        {{--</div>--}}

                    {{--</div>--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{----}}

    @if($activeModules->where('code_name', 'appraisal')->first())
        <div class="row">
            <div class="col-md-12">
                <!-- Employee Monthly performance Widget-->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Employee Monthly Appraisal</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-center">
                                    <strong>My Performance For {{ date('Y') }}</strong>
                                </p>

                                <div class="chart">
                                    <!-- Sales Chart Canvas-->
                                    <canvas id="empMonthlyPerformanceChart" style="height: 220px;"></canvas>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- Appraised months list col -->
                            <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Appraised Months List</strong>
                                </p>
                                <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked" id="emp-appraised-month-list"></ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- Loading wheel overlay -->
                    <div class="overlay" id="loading_overlay_emp_monthly_appraisal">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <!-- /.box Employee Monthly performance Widget -->
            </div>
            <!-- /.col -->
        </div>
    @endif

    @if($activeModules->where('code_name', 'appraisal')->first())
        <div class="row">
            <div class="col-md-12">
            @if($canViewCPWidget)
                <!-- company performance Widget -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Company Appraisal</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row" id="myStaffPerformanceRankingRow" hidden>
                                <div class="col-md-12">
                                    <p class="text-center"><strong>My Staff Performance Ranking
                                            For {{ date('Y') }}</strong></p>
                                    <div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
                                        <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                            id="my-staff-ranking-list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="topLvlDivGraphAndRankingRow">
                                <!-- Chart col -->
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>
                                            @if($isSuperuser)
                                                {{ $topGroupLvl->plural_name }}
                                            @elseif($isDivHead)
                                                {{ $managedDivsLevel->plural_name }}
                                            @endif
                                            Performance For {{ date('Y') }}
                                        </strong>
                                    </p>

                                    <div class="chart">
                                        <!-- Sales Chart Canvas-->
                                        <canvas id="divisionsPerformanceChart" style="height: 220px;"></canvas>
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>
                                <!-- Ranking col -->
                                <div class="col-md-4">
                                    <p class="text-center">
                                        <strong>Ranking</strong>
                                    </p>
                                    <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                        <ul class="nav nav-pills nav-stacked" id="ranking-list">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- Loading wheel overlay -->
                        <div class="overlay" id="lo_company_appraisal">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                        <!-- Include division performance modal -->
                    @include('dashboard.partials.division_4_performance_modal')
                    @include('dashboard.partials.division_3_performance_modal')
                    @include('dashboard.partials.division_2_performance_modal')
                    @include('dashboard.partials.division_1_performance_modal')
                    <!-- Include emp list performance modal -->
                    @include('dashboard.partials.emp_list_performance_modal')
                    <!-- Include emp year performance modal -->
                        @include('dashboard.partials.emp_year_performance_modal')
                    </div>
                    <!-- /.box company performance Widget -->
                @endif
            </div>
            <!-- /.col -->
        </div>
    @endif

    @if($activeModules->where('code_name', 'appraisal')->first())
        @if($canViewEmpRankWidget)
            <div class="row">
                <div class="col-md-12">
                    <!-- Employees Performance Ranking Widget -->
                    <div class="box box-success same-height-widget" id="empPerformanceRankingWidgetBox">
                        <div class="box-header with-border">
                            <h3 class="box-title">Employees Ranking</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <!-- Emp Group Filters (divisions) -->
                            <div class="col-sm-4 border-right">
                                <p class="text-center">
                                    <strong>Filters</strong>
                                </p>
                                <form>
                                    @foreach($divisionLevels as $divisionLevel)
                                        <div class="form-group">
                                            <label for="{{ 'division_level_' . $divisionLevel->level }}"
                                                   class="control-label">{{ $divisionLevel->name }}</label>

                                            <select id="{{ 'division_level_' . $divisionLevel->level }}"
                                                    name="{{ 'division_level_' . $divisionLevel->level }}"
                                                    class="form-control input-sm select2"
                                                    onchange="divDDEmpPWOnChange(this, $('#emp-top-ten-list'), $('#emp-bottom-ten-list'), parseInt('{{ $totNumEmp }}'), $('#loading_overlay_emp_performance_ranking'))"
                                                    style="width: 100%;">
                                            </select>
                                        </div>
                                    @endforeach
                                </form>
                            </div>
                            <!-- /.Emp Group Filters (divisions) -->
                            <!-- Top ten -->
                            <div class="col-sm-4 border-right">
                                <p class="text-center">
                                    <strong class="label label-success"><i class="fa fa-level-up"></i> Top 10 Employees</strong>
                                </p>
                                <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                        id="emp-top-ten-list">
                                    </ul>
                                </div>
                            </div>
                            <!-- Bottom ten -->
                            <div class="col-sm-4">
                                <p class="text-center">
                                    <strong class="label label-danger"><i class="fa fa-level-down"></i> Bottom 10
                                        Employees</strong>
                                </p>
                                <div class="no-padding" style="max-height: 274px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked products-list product-list-in-box"
                                        id="emp-bottom-ten-list">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <!-- Loading wheel overlay -->
                        <div class="overlay" id="loading_overlay_emp_performance_ranking">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </div>
                    <!-- /.Employees Performance Ranking Widget -->
                </div>
            </div>
        @endif
    @endif

    @if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
        <div class="row">
            <div class="col-md-7">
                <!-- Include tasks widget -->
                @include('dashboard.partials.widgets.tasks_widget')
            </div>
            <div class="col-md-5">
                <!-- Include tasks to check widget -->
                @include('dashboard.partials.widgets.tasks_to_check_widget')
            </div>
        </div>
    @endif

    @if($activeModules->where('code_name', 'appraisal')->first())
        <div class="row">
            <div class="col-md-12">
                <!-- Available Perks Widgets -->
                <div class="box box-warning same-height-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Available Perks</h3>

                        <div class="box-tools pull-right">
                            <!-- <span class="label label-warning">8 New Members</span> -->
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <ul class="users-list clearfix" id="perks-widget-list">
                        </ul>
                        <!-- /.users-list -->
                    </div>
                    <!-- /.box-body -->
                    <!-- include perk details modal -->
                    @include('appraisals.partials.edit_perk', ['isReaOnly' => true])
                </div>
                <!-- /.Available Perks Widgets -->
            </div>
        </div>
    @endif
         @if($activeModules->where('code_name', 'induction')->first())
           <div class="row">
             <div class="col-md-12">
             <div class="box box-muted same-height-widget">
               <div class="box-header with-border">
                <i class="material-icons">school</i>
                 <h3 class="box-title">Induction</h3>

                 <div class="box-tools pull-right">
                   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                   </button>
                   <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                 </div>
               </div>
               <!-- /.box-header -->
               <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                    <table class="table table-striped table-bordered">

                        <tr>
                           <!--  <th style="width: 10px">#</th> -->
                            <th>Induction Name</th>
                            <th>KAM </th>
                            <th>Client</th>
                            <th style="text-align: center;"><i class="fa fa-info-circle"></i> Status</th>
                        </tr> 
                        
                       @if (!empty($ClientInduction))
                           @foreach($ClientInduction as $Induction)
                             <tr>
                               <!--  <td>{{ $Induction->completed_task }}</td> -->
                                <td>{{ (!empty($Induction->induction_title)) ?  $Induction->induction_title : ''}}</td>
                                <td>{{ !empty($Induction->firstname) && !empty($Induction->surname) ? $Induction->firstname.' '.$Induction->surname : '' }}</td>
                                 <!-- <td>{{ (!empty($Induction->create_by)) ?  $Induction->create_by : ''}}</td> -->
                           <td>{{ (!empty($Induction->company_name)) ?  $Induction->company_name : ''}} </td>
                           <td>
						   <div class="progress xs">
                                <div class="progress-bar progress-bar-warning  progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:{{ $Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)  }}%"></div></div>
                           {{  (round($Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)))}}% </td>
                             </tr>
                           @endforeach
                       @endif
                    </table>
                 </div>
               <!-- </div> -->
             </div>
           </div>
       </div>
    @endif 
    @if($activeModules->where('code_name', 'induction')->first())
        <div class="row">
            <div class="col-md-12">
                <div class="box box-muted same-height-widget">
                    <div class="box-header with-border">
                        <i class="material-icons">school</i>
                        <h3 class="box-title">Induction</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <table class="table table-striped table-bordered">

                            <tr>
                                <!--  <th style="width: 10px">#</th> -->
                                <th>Induction Name</th>
                                <th>KAM</th>
                                <th>Client</th>
                                <th style="text-align: center;"><i class="fa fa-info-circle"></i> Status</th>
                            </tr>

                            @if (!empty($ClientInduction))
                                @foreach($ClientInduction as $Induction)
                                    <tr>
                                    <!--  <td>{{ $Induction->completed_task }}</td> -->
                                        <td>{{ (!empty($Induction->induction_title)) ?  $Induction->induction_title : ''}}</td>
                                        <td>{{ !empty($Induction->firstname) && !empty($Induction->surname) ? $Induction->firstname.' '.$Induction->surname : '' }}</td>
                                    <!-- <td>{{ (!empty($Induction->create_by)) ?  $Induction->create_by : ''}}</td> -->
                                        <td>{{ (!empty($Induction->company_name)) ?  $Induction->company_name : ''}}</td>
                                        <td>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-warning  progress-bar-striped"
                                                     role="progressbar"
                                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                     style="width:{{ $Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)  }}%"> {{  (round($Induction->completed_task == 0 ? 0 : ($Induction->completed_task/$Induction->total_task * 100)))}}
                                                    %
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    @endif
    <!--  -->
    @if($activeModules->where('code_name', 'leave')->first())
        <div class="row">
            <div class="col-md-6">
                <!-- /Tasks List -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-hourglass"></i>
                        <h3 class="box-title">Leave Balance</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th style="text-align: right;"><i class="material-icons">account_balance_wallet</i>Leave
                                        Balance
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (!empty($balances))
                                    @foreach($balances as $balance)
                                        <tr>
                                            <td>{{ (!empty($balance->leavetype)) ?  $balance->leavetype : ''}}</td>
                                            <td style="text-align: right;">{{ (!empty($balance->leave_balance)) ?  $balance->leave_balance / 8: 0}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="box-footer">
                                <!--  <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button> -->
                                <button id="Apply" class="btn btn-primary pull-right"><i
                                            class="fa fa-cloud-download"></i> Apply For Leave
                                </button>
                            </div>
                        </div>
                        @if(Session('error_starting'))
                            @include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
                        @endif
                        @include('tasks.partials.end_task')
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /Tasks List End -->
            </div>

            <div class="col-md-6">
                <!-- /Tasks List -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-hourglass"></i>
                        <h3 class="box-title">Leave Applied For Status</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                        <div class="table-responsive">
                            <table class="table no-margin">

                                <thead>
                                <tr>
                                    <th><i class="material-icons">shop_two</i> Leave Type</th>
                                    <th><i class="fa fa-calendar-o"></i> Date From</th>
                                    <th><i class="fa fa-calendar-o"></i> Date To</th>
                                    <th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
                                    <th style="text-align: right;"><i class="fa fa-info-circle"></i> Rejection Reason
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!empty($application))
                                    @foreach($application as $app)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ (!empty($app->leavetype)) ?  $app->leavetype : ''}}</td>
                                            <td style="vertical-align: middle;">
                                                {{ !empty($app->start_date) ? date('d M Y ', $app->start_date) : '' }}
                                            </td>
                                            <td style="vertical-align: middle;">{{ !empty($app->end_date) ? date('d M Y ', $app->end_date) : '' }}</td>
                                            <td style="text-align: right; vertical-align: middle;">
                                                {{ (!empty($app->status) && $app->status > 0) ? $leaveStatusNames[$app->status]." ".$app->reject_reason  : ''}}
                                            </td>
                                            <td style="text-align: right; vertical-align: middle;">
                                                {{ !empty($app->reject_reason) ? $app->reject_reason  : 'N/A'}}
                                            </td>
                                            <td class="text-right" style="vertical-align: middle;">
                                                @if(in_array($app->status, [2, 3, 4, 5]))
                                                    <button class="btn btn-xs btn-warning"
                                                            title="Cancel Leave Application" data-toggle="modal"
                                                            data-target="#cancel-leave-application-modal"
                                                            data-leave_application_id="{{ $app->id }}"><i
                                                                class="fa fa-times"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Include cancellation reason modal -->
                    @include('dashboard.partials.cancel_leave_application_modal')
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="ion ion-ios-people-outline"></i>
                        <h3 class="box-title">People On Leave This Month</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding" style="max-height: 180px; overflow-y: scroll;">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Employee</th>
                                <th class="text-center">From</th>
                                <th class="text-center">To</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($onLeaveThisMonth as $employee)
                                <tr>
                                    <td style="vertical-align: middle;"
                                        class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}"
                                        nowrap>{{ $loop->iteration }}.
                                    </td>
                                    <td style="vertical-align: middle;"
                                        class="{{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">
                                        <img src="{{ $employee->profile_pic_url }}" class="img-circle"
                                             alt="Employee's Photo"
                                             style="width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; margin-top: -2px;">
                                        <span>{{ $employee->full_name }}</span>
                                    </td>
                                    <td style="vertical-align: middle;"
                                        class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->start_time) ? date('d M Y H:i', $employee->start_time) : (($employee->start_date) ? date('d M Y', $employee->start_date) : '') }}</td>
                                    <td style="vertical-align: middle;"
                                        class="text-center {{ ($employee->is_on_leave_today) ? 'bg-primary' : '' }}">{{ ($employee->end_time) ? date('d M Y H:i', $employee->end_time) : (($employee->end_date) ? date('d M Y', $employee->end_date) : '') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    @endif
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

    {{--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}
    <script>
        function postData(id, data) {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }

        $(function () {
            // hide end button when page load
            //$("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

            $('#Apply').click(function () {
                location.href = '/leave/application';
            });

            $('#ticket').click(function () {
                location.href = '/helpdesk/ticket';
            });


            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                    dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }

            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

            $(function () {
                $('img').on('click', function () {
                    $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
                    $('#enlargeImageModal').modal('show');
                });
            });
//            CKEDITOR.replace('summary');
            //widgets permissions
            var isSuperuser = parseInt({{ (int) $isSuperuser }}),
                isDivHead = parseInt({{ (int) $isDivHead }}),
                isSupervisor = parseInt({{ (int) $isSupervisor }}),
                canViewCPWidget = parseInt({{ (int) $canViewCPWidget }}),
                canViewTaskWidget = parseInt({{ (int) $canViewTaskWidget }}),
                canViewEmpRankWidget = parseInt({{ (int) $canViewEmpRankWidget }});

            @if($activeModules->where('code_name', 'appraisal')->first())
            //Employees ranking widget
            if (canViewEmpRankWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#empPerformanceRankingWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                //Populate drop down on page load
                var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                var postTo = '{!! route('divisionsdropdown') !!}';
                var selectedOption = '';
                //var divLevel = parseInt('{{ $divisionLevel->level }}');
                var incInactive = -1;
                var loadAll = loadAllDivs;
                        @if($loop->first)
                var selectFirstDiv = 1;
                var divHeadSpecific;
                if (isSuperuser) divHeadSpecific = 0;
                else if (isDivHead) divHeadSpecific = 1;
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                //firstDivDDID = ddID;
                @else
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                @endif
                //parentDDID
                parentDDID = ddID;
                loadAllDivs = -1;
                @endforeach

                //Load top ten performing employees (widget)
                //var topTenList = $('#emp-top-ten-list');
                //loadEmpListPerformance(topTenList, 0, 0, true);

                //Load Bottom ten performing employees (widget)
                //var bottomTenList = $('#emp-bottom-ten-list');
                //var totNumEmp = parseInt('{{ $totNumEmp }}');
                //loadEmpListPerformance(bottomTenList, 0, 0, false, true, totNumEmp);
            }

            if (canViewTaskWidget == 1) {
                //Load divisions drop down
                var parentDDID = '';
                var loadAllDivs = 1;
                var firstDivDDID = null;
                var parentContainer = $('#emptasksWidgetBox');
                @foreach($divisionLevels as $divisionLevel)
                //Populate drop down on page load
                var ddID = '{{ 'division_level_' . $divisionLevel->level }}';
                var postTo = '{!! route('divisionsdropdown') !!}';
                var selectedOption = '';
                //var divLevel = parseInt('{{ $divisionLevel->level }}');
                var incInactive = -1;
                var loadAll = loadAllDivs;
                        @if($loop->first)
                var selectFirstDiv = 1;
                var divHeadSpecific = 1;
                if (isSuperuser) divHeadSpecific = 0;
                else if (isDivHead) divHeadSpecific = 1;
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, selectFirstDiv, divHeadSpecific, parentContainer);
                firstDivDDID = ddID;
                @else
                loadDivDDOptions(ddID, selectedOption, parentDDID, incInactive, loadAll, postTo, null, null, parentContainer);
                @endif
                //parentDDID
                parentDDID = ddID;
                loadAllDivs = -1;
                @endforeach
            }

            //Draw employee performance graph
            var empID = parseInt('{{ $user->person->id }}');
            var empChartCanvas = $('#empMonthlyPerformanceChart');
            var loadingWheel = $('#loading_overlay_emp_monthly_appraisal');
            var empAppraisedMonthList = $('#emp-appraised-month-list');
            loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);

            //Company appraisal
            if (canViewCPWidget == 1) {
                //Draw divisions performance graph [Comp Appraisal Widget]
                var rankingList = $('#ranking-list');
                var divChartCanvas = $('#divisionsPerformanceChart');
                var loadingWheelCompApr = $('#lo_company_appraisal');
                var managerID = parseInt({{ $user->person->id }});
                if (isSuperuser == 1) {
                    var divLevel = parseInt('{{ $topGroupLvl->id }}');
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, null, loadingWheelCompApr);
                }
                else if (isDivHead == 1) {
                    var divLevel = parseInt({{ $managedDivsLevel->level }});
                    loadDivPerformance(divChartCanvas, rankingList, divLevel, null, managerID, loadingWheelCompApr);
                }
                else if (isSupervisor) {
                    $('#topLvlDivGraphAndRankingRow').hide();
                    var staffPerfRow = $('#myStaffPerformanceRankingRow');
                    staffPerfRow.show();
                    rankingList = staffPerfRow.find('#my-staff-ranking-list');
                    loadEmpListPerformance(rankingList, 0, 0, false, false, null, managerID, loadingWheelCompApr);
                }

                //show performance of sub division levels on modals (modal show) [Comp Appraisal Widget]
                var i = 1;
                for (i; i <= 4; i++) {
                    $('#sub-division-performance-modal-' + i).on('show.bs.modal', function (e) {
                        var linkDiv = $(e.relatedTarget);
                        var modalWin = $(this);
                        subDivOnShow(linkDiv, modalWin);
                    });
                    $('#sub-division-performance-modal-' + i).on('hidden.bs.modal', function (e) {
                        $('#lo-sub-division-performance-modal-' + i).show();
                    });
                }

                //show performance of employees on modals [Comp Appraisal Widget]
                $('#emp-list-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var modalWin = $(this);
                    var loadingWheelEmpList = $('#lo-emp-list-performance-modal');
                    empPerOnShow(linkDiv, modalWin);
                });
                $('#emp-list-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-list-performance-modal').show();
                });

                //show employee monthly performance on modal [Comp Appraisal Widget]
                $('#emp-year-performance-modal').on('show.bs.modal', function (e) {
                    var linkDiv = $(e.relatedTarget);
                    var empID = parseInt(linkDiv.data('emp_id'));
                    var empName = linkDiv.data('emp_name');
                    var empChartCanvas = $('#empMonthlyPerformanceModalChart');
                    var loadingWheel = $('#lo-emp-year-performance-modal');
                    var empAppraisedMonthList = $('#emp-appraised-month-modal-list');
                    var modalWin = $(this);
                    modalWin.find('#emp-year-modal-title').html(empName + '  - Appraisal');
                    loadEmpMonthlyPerformance(empChartCanvas, empID, loadingWheel, empAppraisedMonthList);
                });
                $('#emp-year-performance-modal').on('hidden.bs.modal', function (e) {
                    $('#lo-emp-year-performance-modal').show();
                });
            }

            var newsID;
            $('#View-news-modal').on('show.bs.modal', function (e) {
                //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                newsID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                var summary = $('<textarea />').html(btnEdit.data('summary')).text();
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#summary').val(summary);
            });
            //Show available perks on the perks widget
            var perksWidgetList = $('#perks-widget-list');
            loadAvailablePerks(perksWidgetList);

            //Show perk details
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var perkLink = $(e.relatedTarget);
                var modal = $(this);
                perkDetailsOnShow(perkLink, modal);
            });
            @endif

            @if($activeModules->where('code_name', 'leave')->first())
            //leave status (widget)
            var LeaveStatus = $('#leave-status-list');
            //loadLeaveStatus();

            //leave cancellation reason form on show
            var cancelApplicationModal = $('#cancel-leave-application-modal');
            var leaveApplicationID;
            cancelApplicationModal.on('show.bs.modal', function (e) {
                //console.log('gets here');
                var btnCancel = $(e.relatedTarget);
                leaveApplicationID = btnCancel.data('leave_application_id');
                //var modal = $(this);
                //modal.find('#task_id').val(taskID);
            });

            //perform leave application cancellation
            cancelApplicationModal.find('#cancel-leave-application').on('click', function () {
                var strUrl = '/leave/application/' + leaveApplicationID + '/cancel';
                var formName = 'cancel-leave-application-form';
                var modalID = 'cancel-leave-application-modal';
                var submitBtnID = 'cancel-leave-application';
                var redirectUrl = '/';
                var successMsgTitle = 'Leave Application Cancelled!';
                var successMsg = 'Your leave application has been cancelled!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
            @endif

            @if($activeModules->whereIn('code_name', ['induction', 'tasks', 'meeting'])->first())
            document.getElementById("notes").placeholder = "Enter Task Note or Summary";
            //Post end task form to server using ajax (add)
            var taskID;
            var employeeID;
            var uploadRequired;
            $('#end-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                employeeID = btnEnd.data('employee_id');
                uploadRequired = btnEnd.data('upload_required');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
                modal.find('#employee_id').val(employeeID);
                modal.find('#upload_required').val(uploadRequired);
            });

            $('#end-task').on('click', function () {
                endTask(taskID);
                /*
                var strUrl = '/task/end';
                var formName = 'end-task-form';
                var modalID = 'end-task-modal';
                var submitBtnID = 'end-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Ended!';
                var successMsg = 'Task has been Successfully ended!';

                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                */
            });

            $('#close-task-modal').on('show.bs.modal', function (e) {
                var btnEnd = $(e.relatedTarget);
                taskID = btnEnd.data('task_id');
                var modal = $(this);
                modal.find('#task_id').val(taskID);
            });

            $('#close-task').on('click', function () {
                var strUrl = '/task/check';
                var formName = 'close-task-form';
                var modalID = 'close-task-modal';
                var submitBtnID = 'close-task';
                var redirectUrl = '/';
                var successMsgTitle = 'Task Checked!';
                var successMsg = 'Task has been Successfully checked!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Launch counter for running tasks
            @foreach($tasks as $task)
            increment({{ $task->task_id }});
            @endforeach
            @endif

            //Show success action modal
            //$('#success-action-modal').modal('show');

            $(window).load(function () {
                $('#myCarousel').carousel({
                    interval: 5000
                })
            });

            //


        });
    </script>
@endsection