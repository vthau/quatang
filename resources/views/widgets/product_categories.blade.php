<?php
$apiFE = new \App\Api\FE;
$categories = $apiFE->getProductCategories();
?>

@if (count($categories))
<div class="block-sidebar block-sidebar-categorie">
    <div class="block-title"><strong>danh mục sản phẩm</strong></div>
    <div class="block-content">
        <ul class="items">
            @foreach ($categories as $category)
                <li class="parent">
                    <a href="{{$category->getHref()}}">
                        {{$category->getTitle()}}
                    </a>

                    @if (count($category->getSubCategories()))
                        <span class="toggle-submenu"></span>
                        <ul class="subcategory">
                            @foreach ($category->getSubCategories() as $sub)
                                <li>
                                    <a href="{{$sub->getHref()}}">
                                        {{$sub->getTitle()}}
                                    </a>

                                    @if (count($sub->getSubCategories()))
                                        <span class="toggle-submenu"></span>
                                        <ul class="subcategory leaf-cate">
                                            @foreach ($sub->getSubCategories() as $child)
                                                <li>
                                                    <a href="{{$child->getHref()}}">
                                                        {{$child->getTitle()}}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
