<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create Permissions
        $permissions = [
                'الفواتير',
                'قائمة الفواتير',
                'الفواتير المدفوعة',
                'الفواتير المدفوعة جزئيا',
                'الفواتير الغير مدفوعة',
                'ارشيف الفواتير',
                'التقارير',
                'تقرير الفواتير',
                'تقرير العملاء',
                'المستخدمين',
                'قائمة المستخدمين',
                'صلاحيات المستخدمين',
                'الاعدادات',
                'المنتجات',
                'الاقسام',
        
                'اضافة فاتورة',
                'حذف الفاتورة',
                'تصدير EXCEL',
                'تغير حالة الدفع',
                'تعديل الفاتورة',
                'ارشفة الفاتورة',
                'طباعةالفاتورة',
                'اضافة مرفق',
                'حذف المرفق',
        
                'اضافة مستخدم',
                'تعديل مستخدم',
                'حذف مستخدم',
        
                'عرض صلاحية',
                'اضافة صلاحية',
                'تعديل صلاحية',
                'حذف صلاحية',
        
                'اضافة منتج',
                'تعديل منتج',
                'حذف منتج',
        
                'اضافة قسم',
                'تعديل قسم',
                'حذف قسم',
                'الاشعارات',
            ];
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }

            // Create User
            $user = User::create([
                                    'name' => 'admin',
                                    'email' => 'admin@admin.com',
                                    'password' => bcrypt('123456789'),
                                    'roles_name' => ["owner"],
                                    'status' => 'مفعل'
                                ]);
                $role = Role::create(['name' => 'owner']);
                $permissions = Permission::pluck('id','id')->all();
                $role->syncPermissions($permissions);
                $user->assignRole([$role->id]);
                
            // Create Section
            $section = Section::create([
                            'name'          => 'القسم الاول',
                            'description'   => 'تفاصيل القسم الاول',
                            'created_by'    => $user->name
                        ]);

            // Create Product
            $product = Product::create([
                                'name'          => 'المنتج الاول',
                                'description'   => 'تفاصيل المنتج الاول',
                                'section_id'    =>  $section->id
                            ]);
    }
}