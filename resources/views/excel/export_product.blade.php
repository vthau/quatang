<?php
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile();
$isMobile = $apiMobile->isMobile();

$viewer = $apiCore->getViewer();

?>

<table border="1">
    <tr>
        <th>mã</th>
        <th>tên sản phẩm</th>
        <th>nhóm sản phẩm chính</th>
        <th>nhóm sản phẩm phụ</th>
        <th>thương hiệu</th>
        <th>xuất xứ</th>
        <th>giá gốc</th>
        <th>giảm giá (%)</th>
        <th>giá sau giảm</th>
        <th>số lượng</th>
        <th>kích thước dài x rộng x cao (cm)</th>
        <th>khối lượng (g)</th>
        <th>khối lượng bao bì (g)</th>
        <th>lượt mua</th>
        <th>lượt xem</th>
        <th>lượt thích</th>
        <th>trạng thái</th>
        <th>hàng sắp về</th>
        <th>sản phẩm mới</th>
        <th>sản phẩm bán chạy</th>
        <th>đơn vị</th>
        <th>cân nặng (kg)</th>
        <th>thể tích (ml)</th>
        <th>kích thước</th>
        <th>kích thước vòng hông</th>
        <th>giới tính</th>
        <th>dạng sản phẩm</th>
        <th>điểm thấm hút</th>
        <th>mức thấm hút</th>
        <th>qui cách đóng gói</th>
        <th>số miếng</th>
        <th>từ khóa SEO</th>
        <th>mô tả ngắn</th>
        <th>video URL</th>
        <th>thông tin chi tiết</th>
    </tr>
    @if (count($items))
        <?php foreach($items as $product):
        $cateOthers = $product->getCategoryOthers();
        $quantity = '';
        if (!$product->unlimited) {
            $quantity = $product->quantity;
        }
        ?>
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->title}}</td>
            <td>{{$product->getCategory() ? $product->getCategory()->getTitle() : ''}}</td>
            <td>
                @if(count($cateOthers))
                    <?php foreach ($cateOthers as $ite):

                    ?>
                    {{$ite->getTitle() . '; '}}
                    <?php endforeach;?>
                @endif
            </td>
            <td>{{$product->getBrand() ? $product->getBrand()->getTitle() : ''}}</td>
            <td>{{$product->getLabel('made_in')}}</td>
            <td>{{$product->price_main}}</td>
            <td>{{$product->discount}}</td>
            <td>{{$product->price_pay}}</td>
            <td>{{$quantity}}</td>
            <td>{{$product->chieu_dai . ' x ' . $product->chieu_rong . ' x ' . $product->chieu_cao}}</td>
            <td>{{$product->khoi_luong}}</td>
            <td>{{$product->khoi_luong_bao_bi}}</td>
            <td>{{$product->sold_count}}</td>
            <td>{{$product->view_count}}</td>
            <td>{{$product->love_count}}</td>
            <td>{{$product->status == "con_hang" ? 'Còn Hàng' : 'Hết Hàng'}}</td>
            <td>{{$product->hang_sap_ve ? 1 : 0}}</td>
            <td>{{$product->is_new ? 1 : 0}}</td>
            <td>{{$product->is_best_seller ? 1 : 0}}</td>
            <td>{{$product->don_vi}}</td>
            <td>{{$product->can_nang}}</td>
            <td>{{$product->the_tich}}</td>
            <td>{{$product->kich_thuoc}}</td>
            <td>{{$product->kich_thuoc_vong_hong}}</td>
            <td>{{$product->gioi_tinh}}</td>
            <td>{{$product->dang_san_pham}}</td>
            <td>{{$product->diem_tham_hut}}</td>
            <td>{{$product->muc_tham_hut}}</td>
            <td>{{$product->qui_cach_dong_goi}}</td>
            <td>{{$product->so_mieng}}</td>
            <td>{{$product->tu_khoa_seo}}</td>
            <td>{{$product->mo_ta_ngan}}</td>
            <td>{{$product->video_link}}</td>
            <td>{{$product->thong_tin_chi_tiet}}</td>
        </tr>
        <?php endforeach;?>
    @endif
</table>
