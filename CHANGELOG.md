## 更新日志

### 2020-12-07

* 增加排序实现（前端表格字段表头排序场景）

  ```php
  # 实现方式，参考 App\Web\Backend\Controllers\Admin\SiteController
  ## 1、控制器增加 $form->sortable();
  ## 2、查询请求规则类（FormRequest）增加 fetchSortKeyItems() 方法；
  ## 3、查询请求规则类（FormRequest）属性 $fillable 增加接收参数名：$orderByField。
  
  # 注意，可能需要修改属性和方法：
  ## 1、接收参数名： $orderByField；
  ## 2、数据表主键字段名：$orderByPrimaryKey;
  ## 3、根据接收字段考虑重写方法：sortable()。
  ```