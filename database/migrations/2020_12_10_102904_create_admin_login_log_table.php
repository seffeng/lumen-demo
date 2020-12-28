<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Modules\Log\Models\AdminLoginLog;

class CreateAdminLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model = new AdminLoginLog();
        $tableName = $model->getTable();
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->bigIncrements('id')->nullable(false)->comment('日志ID[自增]');
                $table->integer('admin_id')->index()->unsigned()->nullable(false)->default(0)->comment('管理员ID[admin.id]');
                $table->tinyInteger('status_id')->unsigned()->nullable(false)->default(0)->comment('状态');
                $table->tinyInteger('type_id')->unsigned()->nullable(false)->default(0)->comment('类型');
                $table->tinyInteger('from_id')->unsigned()->nullable(false)->default(0)->comment('操作源[后台,api...]');
                $table->tinyInteger('delete_id')->unsigned()->nullable(false)->default(0)->comment('删除类型');
                $table->string('content')->nullable(false)->default('')->comment('内容');
                $table->string('login_ip', 39)->nullable(false)->default('')->comment('登录ip');
                $table->integer('created_at')->unsigned()->nullable(false)->default(0)->comment('添加时间');
                $table->integer('updated_at')->unsigned()->nullable(false)->default(0)->comment('修改时间');
                $table->charset = 'utf8mb4';
                $table->engine = 'InnoDB';
                $table->collation = 'utf8mb4_general_ci';
            });
            $model->getConnection()->statement('ALTER TABLE `'. $model->getTablePrefix() . $tableName .'` COMMENT \'管理员登录日志表\'');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('app.env') === 'local') {
            $model = new AdminLoginLog();
            Schema::dropIfExists($model->getTable());
        }
    }
}
