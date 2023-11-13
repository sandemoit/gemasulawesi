@extends('layouts.web')
@section('content')
    <div class="main-container container" id="main-container">
        <!-- Content -->
        <div class="row row-20">

            <!-- slider -->
            <div class="col-lg-8 order-lg-2">

                <section>
                    <div class="title-list-berita">
                        <span>{{ $rubrik_name }}</span>
                    </div>
                    <div class="thumb mb-4">
                        <article class="entry thumb--size-3 mb-0">
                            <div class="entry__img-holder thumb__img-holder"
                                style="background-image: url('{{ get_post_image($headlineRubrik->post->post_id) }}');">
                                <h4 class="hl__b-subtitle">
                                    <a href="{{ route('category', ['rubrik_name' => $headlineRubrik->post->rubrik->rubrik_name]) }}"
                                        class="hl__link">{{ $headlineRubrik->post->rubrik->rubrik_name }}
                                    </a>
                                </h4>
                                <div class="bottom-gradient rubrik"></div>
                                <div class="thumb-text-holder rubrik thumb-text-holder--2">
                                    <ul class="entry__meta">
                                        <li>
                                            <a href="{{ route('category', ['rubrik_name' => $headlineRubrik->post->rubrik->rubrik_name]) }}"
                                                class="entry__meta-category entry__meta-category--label entry__meta-category--tosca">{{ $headlineRubrik->post->rubrik->rubrik_name }}</a>
                                        </li>
                                    </ul>
                                    <h2 class="title-category">
                                        <a
                                            href="{{ route('singlePost', [
                                                'rubrik' => str_replace(' ', '-', $headlineRubrik->post->rubrik->rubrik_name),
                                                'post_id' => $headlineRubrik->post->post_id,
                                                'slug' => $headlineRubrik->post->slug,
                                            ]) }}">{{ $headlineRubrik->post->title }}</a>
                                    </h2>
                                    <ul class="entry__meta">
                                        <li class="entry__meta-comments">
                                            <a> {{ convert_date_to_ID($headlineRubrik->created_at) }} </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </article>
                        <article class="thumb-text-down">
                            <div class="hl__b-title">
                                <a href="{{ route('singlePost', [
                                    'rubrik' => str_replace(' ', '-', $headlineRubrik->post->rubrik->rubrik_name),
                                    'post_id' => $headlineRubrik->post->post_id,
                                    'slug' => $headlineRubrik->post->slug,
                                ]) }}"
                                    class="hl__link">
                                    {{ $headlineRubrik->post->title }}
                                </a>
                            </div>
                            <ul class="entry__meta">
                                <li class="entry__meta-comments">
                                    <a> {{ convert_date_to_ID($headlineRubrik->created_at) }} </a>
                                </li>
                            </ul>
                        </article>
                    </div>

                    <!-- Berita Terkini -->
                    <div class="berita-terkini">
                        <div class="title-list-berita">
                            <span>Berita Terkini</span>
                        </div>
                        <div class="row">
                            <div class="col">
                                <ul class="post-list-small post-list-small--2 mb-32">
                                    @foreach ($beritaTerkini[0] as $post)
                                        <li class="post-list-small__item">
                                            <article class="post-list-small__entry clearfix">
                                                <div class="post__img">
                                                    <a
                                                        href="{{ route('singlePost', [
                                                            'rubrik' => str_replace(' ', '-', $post->rubrik?->rubrik_name),
                                                            'post_id' => $post->post_id,
                                                            'slug' => $post->slug,
                                                        ]) }}">
                                                        <img data-src="{{ get_post_image($post->post_id) }}"
                                                            src="{{ url('assets/frontend') }}/img/empty.png" alt=""
                                                            class="lazyload">
                                                    </a>
                                                </div>
                                                <div class="post-list-small__body">
                                                    <ul class="entry__meta category underline">
                                                        <li>
                                                            <a href="{{ route('category', ['rubrik_name' => $post->rubrik?->rubrik_name]) }}"
                                                                class="entry__meta-category">{{ $post->rubrik?->rubrik_name }}</a>
                                                        </li>
                                                    </ul>
                                                    <h3 class="post-list-small__entry-title">
                                                        <a href="{{ route('singlePost', [
                                                            'rubrik' => str_replace(' ', '-', $post->rubrik?->rubrik_name),
                                                            'post_id' => $post->post_id,
                                                            'slug' => $post->slug,
                                                        ]) }}"
                                                            class="post-title">{{ $post->title }}</a>
                                                    </h3>
                                                    <p class="bt__date">{{ convert_date_to_ID($post->created_at) }}</p>
                                                </div>
                                            </article>
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Ad Banner 728 -->
                                <div class="text-center pb-48">
                                    <a href="#">
                                        <img src="{{ url('assets/frontend') }}/img/content/placeholder_728.jpg"
                                            alt="">
                                    </a>
                                </div>

                                <x-topik_khusus :$topikKhusus />
                            </div>
                        </div>
                    </div>
                </section>
            </div> <!-- end slider -->

            <!-- Sidebar -->
            <x-sidebar />
            <!-- end sidebar -->
        </div> <!-- end content -->

        {{-- row bawah --}}
        <div class="row row-20 mt-3">
            <div class="col-lg-8 order-lg-2">
                <section>
                    <div class="row">
                        <div class="col">
                            <ul class="post-list-small post-list-small--2 mb-32">
                                @foreach ($beritaTerkini[1] as $post)
                                    <li class="post-list-small__item">
                                        <article class="post-list-small__entry clearfix">
                                            <div class="post__img">
                                                <a
                                                    href="{{ route('singlePost', [
                                                        'rubrik' => str_replace(' ', '-', $post->rubrik->rubrik_name),
                                                        'post_id' => $post->post_id,
                                                        'slug' => $post->slug,
                                                    ]) }}">
                                                    <img data-src="{{ get_post_image($post->post_id) }}"
                                                        src="{{ url('assets/frontend') }}/img/empty.png" alt=""
                                                        class="lazyload">
                                                </a>
                                            </div>
                                            <div class="post-list-small__body">
                                                <ul class="entry__meta category underline">
                                                    <li>
                                                        <a href="{{ route('category', ['rubrik_name' => $post->rubrik?->rubrik_name]) }}"
                                                            class="entry__meta-category">{{ $post->rubrik?->rubrik_name }}</a>
                                                    </li>
                                                </ul>
                                                <h3 class="post-list-small__entry-title">
                                                    <a href="{{ route('singlePost', [
                                                        'rubrik' => str_replace(' ', '-', $post->rubrik?->rubrik_name),
                                                        'post_id' => $post->post_id,
                                                        'slug' => $post->slug,
                                                    ]) }}"
                                                        class="post-title">{{ $post->title }}</a>
                                                </h3>
                                                <p class="bt__date">{{ convert_date_to_ID($post->created_at) }}</p>
                                            </div>
                                        </article>
                                    </li>
                                @endforeach
                            </ul>


                            <!-- Ad Banner 728 -->
                            <div class="text-center pb-48">
                                <a href="#">
                                    <img src="{{ url('assets/frontend') }}/img/content/placeholder_728.jpg" alt="">
                                </a>
                            </div>

                            <div class="paging paging--page">
                                {{ $paginatedPost->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </section> <!-- end carousel posts -->
            </div>

        </div>
    </div> <!-- end main container -->
@endsection
