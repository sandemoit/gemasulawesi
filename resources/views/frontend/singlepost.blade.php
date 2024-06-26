@extends('layouts.web')
@section('content')
    <!-- Breadcrumbs -->

    <div class="main-container container" id="main-container">

        <!-- Content -->
        <div class="row">

            <!-- post content -->
            <div class="col-lg-8 blog__content mb-3">
                <x-ad-item position='above_content' :width="970" :height="250" />
                <div class="container">
                    <ul class="breadcrumbs">
                        <li class="breadcrumbs__item">
                            <a href="{{ url('/') }}" class="breadcrumbs__url"><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumbs__item">
                            <a href="{{ route('category', ['rubrik_name' => Str::slug($post->rubrik->rubrik_name)]) }}"
                                class="breadcrumbs__url">{{ $post->rubrik->rubrik_name }}</a>
                        </li>
                    </ul>
                </div>
                <div class="meta-single-post">
                    <h1 class="title-single-post single-post__title-single-post">
                        {{ $post->title }}
                    </h1>
                    <div class="entry__meta-holder">
                        <ul class="entry__meta">
                            <li class="entry__meta-author">
                                <span><a
                                        href="{{ route('author', ['id' => $post->author_id, 'name' => Str::slug($post->author->display_name)]) }}">{{ $post->author->display_name }}</a></span>
                            </li>
                            <li class="entry__meta-date">
                                {{ convert_date_to_ID($post->published_at) }}
                            </li>
                        </ul>
                    </div>

                    <x-ad-item position='below_heading' :width="970" :height="250" />
                    <div class="social-post socials--medium socials--rounded">
                        <a href="#" target="_blank" class="social social-facebook" id="share-facebook-top"
                            aria-label="facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" target="_blank" class="social social-twitter" id="share-twitter-top"
                            aria-label="twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" target="_blank" class="social social-whatsapp" id="share-whatsapp-top"
                            aria-label="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" target="_blank" class="social social-telegram" id="share-telegram-top"
                            aria-label="telegram"><i class="fa-brands fa-telegram"></i></a>
                        <a href="#" class="social social-copy" id="share-copy-top" aria-label="copy"><i
                                class="fa-solid fa-link"></i></a>
                    </div>
                </div>
                <!-- Entry Image -->
                <div class="thumb image-single-post">
                    <img src="{{ get_post_image($post->post_id) }}" alt="{{ $post->title }}" height="500" width="700">
                    <p class="photo__caption">{!! !empty($post->image) ? strip_tags($post->image->caption) : '' !!}
                        {{ !empty($post->image->source) ? 'Source: ' . strip_tags($post->image->source) : '' }}
                    </p>
                </div>

                <!-- standard post -->
                <article class="entry mb-0">
                    <div class="entry__article-wrap mt-0">
                        <div class="entry__article">
                            <article class="read__content">
                                @php
                                    $article = $post->article;
                                    $bagian1 = '';
                                    $bagian2 = '';
                                    if ($article == "\n") {
                                        $article = '<p></p>';
                                    }
                                    // Buat objek DOMDocument
                                    $dom = new DOMDocument();
                                    libxml_use_internal_errors(true);
                                    if ($dom->loadHTML($article) && !empty($article)) {
                                        // Muat string HTML ke dalam objek DOMDocument
                                        $dom->loadHTML($article);

                                        // Ambil semua elemen paragraf
                                        $paragraphs = $dom->getElementsByTagName('p');

                                        // Hitung jumlah paragraf
                                        $totalParagraphs = $paragraphs->length;

                                        // Tentukan jumlah paragraf per bagian
                                        $paragrafPerBagian = ceil($totalParagraphs / 2);

                                        // Bagian pertama
                                        for ($i = 0; $i < $paragrafPerBagian; $i++) {
                                            $bagian1 .= $dom->saveHTML($paragraphs->item($i));
                                        }

                                        // Bagian kedua
                                        for ($i = $paragrafPerBagian; $i < $totalParagraphs; $i++) {
                                            $bagian2 .= $dom->saveHTML($paragraphs->item($i));
                                        }
                                    }
                                @endphp

                                {!! $bagian1 !!}
                                <!-- Ad Banner 728 -->
                                @php
                                    $ad = get_ad_content();
                                @endphp
                                @if ($ad != null)
                                    <!-- Entry Image (modifikasi untuk menambahkan efek paralaks) -->
                                    <div class="parallax"
                                        style="background-image: url('{{ Storage::url('public/ads/' . $ad->value) }}');"
                                        data-velocity="0.5">
                                    </div>
                                @endif

                                {!! $bagian2 !!}


                                <!-- halaman -->
                                <div class="halaman">
                                    <div class="halaman__teaser">Halaman: </div>
                                    <div class="halaman__wrap">
                                        @for ($i = 1; $i <= $totalPages; $i++)
                                            @php
                                                $pg = $i == 1 ? null : 1;
                                            @endphp
                                            <div class="halaman__item">
                                                <a href="{{ Str::replace(
                                                    '?page=1',
                                                    '',
                                                    route('singlePost', [
                                                        'rubrik' => Str::slug($post->rubrik->rubrik_name),
                                                        'post_id' => $post->post_id,
                                                        'slug' => $post->slug,
                                                        'page' => $i,
                                                    ]),
                                                ) }}"
                                                    class="pagination__page {{ $currentPage == $i ? 'pagination__page--current' : '' }}">
                                                    {{ $i }}
                                                </a>
                                            </div>
                                        @endfor
                                        <div class="halaman__all">
                                            @if ($currentPage < $totalPages)
                                                <a href="{{ route('singlePost', [
                                                    'rubrik' => Str::slug($post->rubrik->rubrik_name),
                                                    'post_id' => $post->post_id,
                                                    'slug' => $post->slug,
                                                    'page' => $currentPage + 1,
                                                ]) }}"
                                                    class="halaman__selanjutnya">Selanjutnya</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="croslink">
                                    <a href="https://news.google.com/publications/CAAiECetNQk0Vqf3knWgGD12R6oqFAgKIhAnrTUJNFan95J1oBg9dkeq?hl=id&gl=ID&ceid=ID:id "
                                        target="_blank" rel="noopener noreferrer">Ikuti Update Berita Terkini Gemasulawesi
                                        di: <strong>Google News</strong></a>
                                </div>

                                <div class="editor__text">
                                    <span>Editor: {{ $post->editor->display_name }}</span>
                                </div>
                                @if ($post->sources != null)
                                    <!-- Sources -->
                                    <div class="editor__text">
                                        <span class="entry__tags-label">Sumber:
                                            @php
                                                if ($post->sources != null and $post->sources != 'null') {
                                                    foreach (json_decode($post->sources) as $source) {
                                                        $source = \App\Models\Source::find($source);
                                                        echo $source->source_name . ' ';
                                                    }
                                                }
                                            @endphp
                                        </span>
                                    </div> <!-- end source -->
                                @endif
                                <!-- tags -->
                                <div class="entry__tags">
                                    <i class="ui-tags"></i>
                                    <span class="entry__tags-label">Tags:</span>

                                    @php
                                        if ($post->tags != null and $post->tags != 'null') {
                                            foreach (json_decode($post->tags) as $tags) {
                                                $tag = cache()->remember(
                                                    'tags' . $tags,
                                                    env('CACHE_DURATION'),
                                                    function () use ($tags) {
                                                        return \App\Models\Tags::find($tags);
                                                    },
                                                );
                                                if ($tag) {
                                                    echo '<a href="' .
                                                        route('tags', ['tag_name' => Str::slug($tag->tag_name)]) .
                                                        '" rel="tag">' .
                                                        $tag->tag_name .
                                                        '</a>';
                                                }
                                            }
                                        }
                                    @endphp
                                </div> <!-- end tags -->
                            </article>

                        </div> <!-- end entry article -->
                    </div> <!-- end entry article wrap -->

                    <!-- Related Posts -->
                    <section class="section mt-3 mb-0">
                        <div class="title-wrap title-wrap--line">
                            <h4 style="text-align: center">Share:</h4>
                            <div class="social-post socials--medium socials--rounded">
                                <a href="#" target="_blank" class="social social-facebook" id="share-facebook-bottom"
                                    aria-label="facebook"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#" target="_blank" class="social social-twitter" id="share-twitter-bottom"
                                    aria-label="twitter"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" target="_blank" class="social social-whatsapp"
                                    id="share-whatsapp-bottom" aria-label="whatsapp"><i
                                        class="fa-brands fa-whatsapp"></i></a>
                                <a href="#" target="_blank" class="social social-telegram"
                                    id="share-telegram-bottom" aria-label="telegram"><i
                                        class="fa-brands fa-telegram"></i></a>
                                <a href="#" class="social social-copy" id="share-copy-bottom" aria-label="copy"><i
                                        class="fa-solid fa-link"></i></a>
                            </div>
                        </div>

                    </section> <!-- end related posts -->
                    <x-ad-item position='below_heading' :width="970" :height="250" />

                </article> <!-- end standard post -->

                @if ($post->allow_comment == 1)
                    <x-comment />
                @endif
            </div> <!-- end post content -->

            <!-- Sidebar -->
            <x-sidebar />
            <!-- end sidebar -->

        </div> <!-- end content -->

        <div class="row row-20">
            @if ($post->related_articles != null and $post->related_articles != 'null')
                <div class="col-lg-8 order-lg-2">

                    <div class="title-post">
                        <span>Berita Terkait</span>
                    </div>
                    <div class="berita__terkait">
                        <ul class="terkait__list">
                            @foreach (json_decode($post->related_articles) as $related)
                                @php
                                    $related = cache()->remember(
                                        'related' . $related,
                                        env('CACHE_DURATION'),
                                        function () use ($related) {
                                            return \App\Models\Posts::with(['rubrik'])->find($related);
                                        },
                                    );
                                @endphp
                                <li>
                                    <h2 class="terkait__title">
                                        @if ($related)
                                            <a href="{{ route('singlePost', [
                                                'rubrik' => Str::slug($related->rubrik->rubrik_name),
                                                'post_id' => $related->post_id,
                                                'slug' => $related->slug,
                                            ]) }}"
                                                class="terkait__link">{{ $related->title }}</a>
                                        @endif
                                    </h2>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="col-lg-8 order-lg-2 mt-4">

                <div class="title-post">
                    <span>Berita Terkini</span>
                </div>
                <div class="row">
                    <div class="col">
                        @foreach ($beritaTerkini as $post_item)
                            @php
                                $currentPostId = request()->segment(3);
                                $isCurrentPost = $currentPostId == $post_item->post_id;
                            @endphp
                            @if (!$isCurrentPost)
                                <ul class="post-list-small post-list-small--2 mb-32 mt-3">
                                    <li class="post-list-small__item">
                                        <article class="post-list-small__entry clearfix">
                                            <div class="post__img">
                                                <a
                                                    href="{{ route('singlePost', [
                                                        'rubrik' => Str::slug($post_item->rubrik->rubrik_name),
                                                        'post_id' => $post_item->post_id,
                                                        'slug' => $post_item->slug,
                                                    ]) }}">
                                                    <img data-src="{{ get_post_image($post_item->post_id) }}"
                                                        src="{{ url('assets/frontend') }}/img/empty.jpg"
                                                        alt="{{ $post->title }}" class="lazyload">
                                                </a>
                                            </div>
                                            <div class="post-list-small__body">
                                                <h3 class="post-list-small__entry-title">
                                                    <a href="{{ route('singlePost', [
                                                        'rubrik' => Str::slug($post_item->rubrik->rubrik_name),
                                                        'post_id' => $post_item->post_id,
                                                        'slug' => $post_item->slug,
                                                    ]) }}"
                                                        class="post-title">{{ $post_item->title }}</a>
                                                </h3>
                                                <p class="bt__date">{{ convert_date_to_ID($post_item->published_at) }}</p>
                                            </div>
                                        </article>
                                    </li>
                                </ul>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-8">
            <x-ad-item position='footer' :width="970" :height="250" />
        </div>
    </div>
@endsection
