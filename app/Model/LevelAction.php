<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Api\Core;
use App\Api\FE;
use App\User;
use \Session;
use \Artisan;

class LevelAction extends Item
{
    public $table = 'level_actions';

    protected $fillable = [
        'title', 'type'
    ];

    public function getActions()
    {
        return [
            'setting_home' => 'Quản Lý Trang Chủ',
            'setting_config' => 'Quản Lý Tùy Chỉnh',
            'setting_about' => 'Quản Lý Giới Thiệu',
            'setting_policy' => 'Quản Lý Chính Sách',

            'setting_tin_tuc' => 'Quản Lý Tin Tức',
            'setting_tu_van' => 'Quản Lý Góc Tư Vấn',
            'setting_contact' => 'Quản Lý Liên Hệ',

            'staff_level_view' => 'Xem DS Quyền Truy Cập',
            'staff_level_add' => 'Tạo Quyền Truy Cập',
            'staff_level_edit' => 'Sửa Quyền Truy Cập',
            'staff_level_delete' => 'Xóa Quyền Truy Cập',

            'staff_user_view' => 'Xem DS Nhân Viên',
            'staff_user_add' => 'Tạo Nhân Viên',
            'staff_user_edit' => 'Sửa Nhân Viên',
            'staff_user_delete' => 'Xóa Nhân Viên',
            'staff_user_block' => 'Chặn Truy Cập',
            'staff_user_unblock' => 'Mở Chặn Truy Cập',

            'user_supplier_view' => 'Xem DS Nhà Cung Cấp',
            'user_supplier_add' => 'Tạo Nhà Cung Cấp',
            'user_supplier_edit' => 'Sửa Nhà Cung Cấp',
            'user_supplier_delete' => 'Xóa Nhà Cung Cấp',

            'product_category_view' => 'Xem DS Nhóm Sản Phẩm',
            'product_category_add' => 'Tạo Nhóm Sản Phẩm',
            'product_category_edit' => 'Sửa Nhóm Sản Phẩm',
            'product_category_delete' => 'Xóa Nhóm Sản Phẩm',

            'product_brand_view' => 'Xem DS Thương Hiệu',
            'product_brand_add' => 'Tạo Thương Hiệu',
            'product_brand_edit' => 'Sửa Thương Hiệu',
            'product_brand_delete' => 'Xóa Thương Hiệu',

            'wish_view' => 'Xem DS Câu Chúc',
            'wish_add' => 'Tạo Câu Chúc',
            'wish_edit' => 'Sửa Câu Chúc',
            'wish_delete' => 'Xóa Câu Chúc',

            'card_template_view' => 'Xem DS Mẫu Thiệp',
            'card_template_add' => 'Tạo Mẫu Thiệp',
            'card_template_edit' => 'Sửa Mẫu Thiệp',
            'card_template_delete' => 'Xóa Mẫu Thiệp',

            'system_category_view' => 'Xem DS Nhóm Chủ Đề',
            'system_category_add' => 'Tạo Nhóm Chủ Đề',
            'system_category_edit' => 'Sửa Nhóm Chủ Đề',
            'system_category_delete' => 'Xóa Nhóm Chủ Đề',

            'product_view' => 'Xem DS Sản Phẩm',
            'product_add' => 'Tạo Sản Phẩm',
            'product_edit' => 'Sửa Sản Phẩm',
            'product_delete' => 'Xóa Sản Phẩm',
//            'product_excel_export' => 'Excel Xuất DS Sản Phẩm',
//            'product_excel_import' => 'Excel Tạo Nhiều Sản Phẩm',

            'order_view' => 'Xem DS Đơn Hàng',
            'order_config' => 'Quản Lý Tùy Chỉnh',
//            'order_ghn' => 'Tạo Vận Đơn GHN',
//            'order_manual' => 'Tạo Vận Đơn Tay',
            'order_confirm_paid' => 'Xác Nhận Thanh Toán',
            'order_confirm_delete' => 'Hủy Xác Nhận Thanh Toán',
            'order_shipment' => 'Cập Nhật Trạng Thái',
            'order_delete' => 'Xóa Đơn Hàng',

            'client_view' => 'Xem DS Khách Hàng',
            'client_add' => 'Tạo Khách Hàng',
            'client_reset_password' => 'Reset Mật Khẩu Khách Hàng',
            'client_delete' => 'Xóa Khách Hàng',
        ];
    }

    public function setPermissions()
    {
        $levels = UserLevel::where('id', '<>', 4)
            ->get();
        foreach ($levels as $level) {
            LevelPermission::create([
                'level_id' => $level->id,
                'action_id' => $this->id,
                'is_allowed' => ($level->id > 2) ? 0 : 1,
            ]);
        }
    }

    public function getDescription()
    {
        $actions = $this->getActions();
        return (!empty($actions[$this->title])) ? $actions[$this->title] : $this->title;
    }

    public function getAllowed($levelId)
    {
        $row = LevelPermission::where("level_id", "=", (int)$levelId)
            ->where("action_id", "=", $this->id)
            ->first();
        if (!$row || ($row && !$row->id)) {
            $allow = ($levelId > 2) ? 0 : 1;

            LevelPermission::create([
                'level_id' => $levelId,
                'action_id' => $this->id,
                'is_allowed' => $allow,
            ]);
            return $allow;
        }
        return $row->is_allowed ? true : false;
    }
}
