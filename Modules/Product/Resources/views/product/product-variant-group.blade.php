<form class="form-horizontal" id="form_product_variant_group" action="{{url('product/product-variant-group/'.$product[0]['product_code'])}}" method="POST">
    {{ csrf_field() }}
    <div class="form-body">
        <div class="form-group">
            <label  class="col-md-3 control-label">Product Variant <span class="text-danger">*</span></label>
            <div class="col-md-4">
                <select class="form-control select2" id="select2-product-variant" multiple="multiple" style="width: 100%">
                    <?php
                        $declaration = [];
                        foreach($product_variant as $key=>$val){
                            if(!empty($val['product_variant_parent'])){
                                $declaration[$val['product_variant_parent']['product_variant_name']][] = [
                                    'id_parent' => $val['id_parent'],
                                    'id_product_variant' => $val['id_product_variant'],
                                    'product_variant_name' => $val['product_variant_name']
                                ];
                            }
                        }
                    ?>
                    @foreach($declaration as $key=>$val)
                        <optgroup label="{{$key}}">
                            @foreach($val as $child)
                                <option value="{{$child['id_product_variant']}}|{{$child['id_parent']}}">{{$child['product_variant_name']}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label  class="col-md-3 control-label">Code <span class="text-danger">*</span></label>
            <div class="col-md-4">
                <input class="form-control" id="product-variant-group-code" placeholder="Product Variant Group Code">
            </div>
        </div>
        <div class="form-group">
            <label  class="col-md-3 control-label">Price <span class="text-danger">*</span></label>
            <div class="col-md-4">
                <input class="form-control price" maxlength="11" id="product-variant-group-price" placeholder="Price Product Variant Group">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label">Visible <span class="text-danger">*</span>
            </label>
            <div class="input-icon right">
                <div class="col-md-2">
                    <div class="md-radio-inline">
                        <div class="md-radio">
                            <input type="radio" id="radio-variant-visibility1" name="product_variant_group_visibility" class="md-radiobtn req-type" value="Visible" checked>
                            <label for="radio-variant-visibility1">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> Visible</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="md-radio-inline">
                        <div class="md-radio">
                            <input type="radio" id="radio-variant-visibility2" name="product_variant_group_visibility" class="md-radiobtn req-type" value="Hidden">
                            <label for="radio-variant-visibility2">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> Hidden </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="product-variant-group-id" value="">
        <input type="hidden" id="product-variant-group-code" name="product_variant_group_code" value="">
        <input type="hidden" id="product-variant-group-visibility" name="product_variant_group_visibility" value="">
        <input type="hidden" id="product_base_price_pvg" name="product_base_price_pvg" value="">

        <div class="form-group row">
            <label  class="col-md-3 col-form-label"></label>
            <div class="col-md-4">
                <a onclick="addProductVariantGroup()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Product Variant Group</a>
            </div>
        </div>
        <div style="margin-top: 5%">
            <table class="table table-bordered table-hover" style="width: 70%" id="table-product-variant">
                <thead>
                <th>Product Variant</th>
                <th>Code</th>
                <th>Price</th>
                <th>Visibility</th>
                <th>Action</th>
                </thead>
                <tbody id="product-variant-group-body">
                @foreach($product_variant_group as $key=>$val)
                    <tr>
                        <td>
                            <?php
                            $arr = array_column($val['product_variant_pivot'], 'product_variant_name');
                            $name = implode(',',$arr);
                            echo $name;
                            ?>
                        </td>
                        <td>{{$val['product_variant_group_code']}}</td>
                        <td>{{number_format($val['product_variant_group_price'],0,",",".")}}</td>
                        <td>{{$val['product_variant_group_visibility']}}</td>
                        <td>
                            <a  onclick="deleteRowProductVariant(this, {{$val['id_product_variant_group']}})" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a>
                            <a  onclick="editRowProductVariant(this,{{$key}})" class="btn btn-sm btn-primary" style="margin-left: 2%"><i class="fa fa-pen"></i> Edit</a>
                        </td>
                        <?php
                            $tmp = [];
                            foreach ($val['product_variant_pivot'] as $p){
                                $tmp[] = $p['id_product_variant'].'|'.$p['id_parent'];
                            }
                            $id_edit = implode(',',$tmp);
                            $arr_id = array_column($val['product_variant_pivot'], 'id_product_variant');
                            $id = implode(',',$arr_id);
                        ?>
                        <input type="hidden" id="product-variant-{{$key}}" name="data[{{$key}}][id]" value="{{$id}}">
                        <input type="hidden" id="product-variant-edit-{{$key}}" name="data[{{$key}}][id-edit]" value="{{$id_edit}}">
                        <input type="hidden" id="product-variant-group-code-{{$key}}" name="data[{{$key}}][code]" value="{{$val['product_variant_group_code']}}">
                        <input type="hidden" id="product-variant-price-{{$key}}" name="data[{{$key}}][price]" value="{{(int)$val['product_variant_group_price']}}">
                        <input type="hidden" id="product-variant-group-visibility-{{$key}}" name="data[{{$key}}][visibility]" value="{{$val['product_variant_group_visibility']}}">
                        <input type="hidden" id="product-variant-group-id-{{$key}}" name="data[{{$key}}][group_id]" value="{{$val['id_product_variant_group']}}">
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
<div class="form-actions">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-9">
            <button onclick="submitProductVariant()" class="btn btn-success mr-2">Submit</button>
        </div>
    </div>
</div>
