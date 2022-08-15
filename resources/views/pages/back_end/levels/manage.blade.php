<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$apiPermission = new \App\Api\Permission();
$viewer = $apiCore->getViewer();

$types = [
    'staff' => 'Nhân Sự',
    'product' => 'Sản Phẩm',
    'template' => 'Template',
    'order' => 'Đơn Hàng',
    'client' => 'Khách Hàng',
    'setting' => 'Quản Lý Chung',
];

$embeds = [
    'staff_level_delete', 'staff_user_delete', 'staff_user_block', 'user_supplier_delete',
    'product_category_delete', 'product_brand_delete', 'wish_delete', 'card_template_delete',
    'system_category_delete', 'product_edit', 'product_delete', 'order_config',
    'order_confirm_paid', 'order_confirm_delete', 'order_shipment', 'order_delete',
    'client_delete',
];
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .card-header .select2-container {
            width: 200px !important;
            margin-left: 20px;
        }
    </style>

    <div class="config-levels-wrapper">
        <div class="fade-in">
            <form action="{{url('admin/level/update')}}" method="post" id="frm-add" accept-charset="UTF-8" autocomplete="off">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>
                                    {{$pageTitle}}
                                </strong>
                                <select name="level_id" onchange="changeLevel(this)">
                                    @foreach ($items as $ite)
                                        <option <?php if ($level->id == $ite->id):?>selected="selected"<?php endif;?> value="{{$ite->id}}">{{$ite->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="card-body">

                                <?php foreach ($types as $k => $v):
                                    $actions = $apiPermission->getTypeActions($k);

                                ?>
                                <div class="form-level">
                                    <div class="level-wrapper">
                                        <div class="level-title">{{$v}}</div>
                                        <div class="level-content">
                                            <div class="form-actions">
                                            <?php
                                            $count = 0;
                                            foreach ($actions as $row):
                                                $checked = $row->getAllowed($level->id);

                                            ?>
                                                <?php if (!$count || !($count%3)):?>
                                                <div class="form-group">
                                                <?php endif;?>
                                                    <div class="col-md-4" data-id="{{$row->id}}">
                                                        <div class="form-wrapper">
                                                            <div class="form-check">
                                                                <label class="c-switch c-switch-label c-switch-pill c-switch-danger">
                                                                    <input name="{{$row->title}}" class="c-switch-input" type="checkbox" <?php if ($checked):?>checked="true"<?php endif;?>>
                                                                    <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                                </label>
                                                            </div>
                                                            <label @if(in_array($row->title, $embeds)) class="required" @endif>{{$row->getDescription()}}</label>
                                                        </div>
                                                    </div>
                                                <?php if ((($count%3) == 2) || ($count == count($actions)-1)): ?>
                                                </div>
                                                <?php endif;?>
                                            <?php $count++;
                                            endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(!empty($message))
            @if($message == 'ITEM_UPDATED')
            showMessage(gks.successUPDATE);
            @endif
            @endif
        });

        function changeLevel(ele) {
            var id = jQuery(ele).val();
            openPage('{{url('admin/level/manage?level=')}}' + id);
        }
    </script>
@stop
