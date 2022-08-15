<?php
if (!isset($others) || !count($others)) {
    return;
}
?>

<div class="post-related mt__50 mb__50">
    <h4 class="mg__0 mb__30 tu tc fwb">Bài Viết Khác</h4>

    <div id="shopify-section-blog-template" class="shopify-section nt_section type_isotope">
        <div class="nt_svg_loader dn"></div>
        <div class="articles products nt_products_holder row des_cnt_1 nt_cover ratio4_3 position_8 equal_nt">
            @foreach ($others as $other)
                <article class="post-384305660043 post_nt_loop post_2 col-lg-4 col-md-4 col-12 mb__40">
                    <div class="post-thumbnail pr oh">
                        <a class="db oh bgd" href="{{$other->getHref()}}">
                            <div class="nt_bg_lz pr_lazy_img lazyloaded"
                                 data-bgset="{{$other->getAvatar()}}}}"
                                 data-ratio="1.4988290398126465"
                                 style="background-image: url('{{$other->getAvatar()}}');"
                            >
                            </div>
                        </a>
                        <div class="pa inside-thumb tc cg">
                            <h2 class="post-title fs__14 ls__2 mt__10 mb__5 tu">
                                <a class="chp" href="{{$other->getHref()}}">{{$other->getTitle()}}</a>
                            </h2>
                        </div>
                    </div>
                    <div class="post-content">
                        <a href="{{$other->getHref()}}" class="more-link text-uppercase">Xem chi tiết</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>

</div>
