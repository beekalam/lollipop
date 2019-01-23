<style>
.factor_rows_tdb {
    text-align: right;
    direction: rtl;
    border: 1px solid #999;
    padding: 5px;
}

.factor_rows_td {
    text-align: right;
    direction: rtl;
    padding: 5px;
    border: 1px solid #999;
}

.factor_table {
    border-collapse: collapse;
    border: 1px solid #999;
    margin-top: 10px;
    margin-bottom: 10px;
}

.factor_text_td{
    text-align: right;
    direction: rtl;
    padding: 5px;
}

.factor_value_td{
    text-align: right;
    direction: rtl;
    padding: 5px;
}

.factor_header_text_td {
    border-bottom: 1px solid #999;
    padding-bottom: 15px;
}
</style>
<table style="table-responsive" class="factor_table persian-number" width="100%">
                <tbody>
                <tr>
                    <td colspan="5" class="factor_header_td">
                        <table width="100%">
                            <tbody>
                            <tr>
                                <td colspan="2" class="factor_line" style="border-top: 1px solid #000;height:5px;"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="factor_header_text_td" style="text-align: center;">
                                   فاکتور
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width:100%;">
                                        <tbody>
                                            <tr>
                                                <?php if(isset($factor_num)): ?>
                                                    <td style="" class="factor_text_td">
                                                        شماره فاکتور :
                                                    </td>
                                                    <td class="persian_digits factor_value_td">
                                                        <?php @eisset($factor_num,"--"); ?>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="text-align: left" class="factor_text_td">
                                                تاریخ صدور :
                                            </td>
                                            <td class="persian_digits factor_value_td" style="text-align:left;">
                                                <?php echo convert_gregorian_iso_to_jalali_iso($factor_date); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="factor_line" style="border-bottom: 1px solid #000;height:5px;"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width:100%">
                                        <tbody>
                                        <tr>
                                            <td style="" class="factor_text_td">
                                                نام مشترک : 
                                            </td>
                                            <td class="persian_digits factor_value_td">
                                                <?php echo $customer->first_name . " " . $customer->last_name; ?>
                                            </td>
                                        </tr>
<!--                                         <tr>
                                            <td style="" class="factor_text_td">
                                                آدرس مشترک :
                                            </td>
                                            <td class="persian_digits factor_value_td">

                                            </td>
                                        </tr> -->
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table style="width:100%">
                                        <tbody>
                                        <tr>
                                            <td style="text-align: left" class="factor_text_td">
کد اشتراک :
                                            </td>
                                            <td class="persian_digits factor_value_td" style="text-align:left;">
                                                <?php echo $customer->id; ?>
                                            </td>
                                        </tr>
<!--                                         <tr>
                                            <td style="text-align: left" class="factor_text_td">
تلفن مشترک :
                                            </td>
                                            <td class="persian_digits factor_value_td" style="text-align:left;">
                                                <?php //echo $customer->mobile; ?>                                    
                                            </td>
                                        </tr> -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="factor_line" style="border-bottom:1px solid #000;height:5px;"></td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td class="factor_rows_td" style="">
ردیف
                    </td>
                    <td class="factor_rows_td" id="sharh_td">
شـــــرح
                    </td>
                    <td class="factor_rows_td" style="">
مبلغ
                    </td>
                    <td class="factor_rows_td" style="">
تعداد
                    </td>
                    <td class="factor_rows_td" style="">
مبلغ ردیف
</td>
                </tr>

                <?php $index = 1; ?>
                <?php foreach($extra_services as $v): ?>
                <tr>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo $index++;  ?>
                    </td>
                    <td class="factor_rows_td persian_digits">
                        <?php echo $v->description; ?>
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo number_format($v->price); ?>
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                         <?php echo $v->num; ?>
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo number_format($v->num * $v->price); ?> &nbsp;
ریال
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo $index; ?>
                    </td>
                    <td class="factor_rows_td persian_digits">
                    ورودی
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo number_format($vorodi); ?>
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                        ----
                    </td>
                    <td class="factor_rows_td persian_digits" style="">
                        <?php echo number_format($vorodi); ?> &nbsp;
ریال
                    </td>
                </tr>                
                <?php if($discount_percent): ?>
                 <tr>
                    <td class="factor_rows_tdb" colspan="2">
شرح تخفیف :
                    </td>
                    <td class="factor_rows_tdb" colspan="2" style="text-align:left">
تخفیف :
                    </td>
                    <td class="factor_rows_tdb persian_digits" style="width:120px">
                        <?php echo number_format($discount); ?> ریال
                    </td>
                </tr>
                <?php endif; ?>
                
<!--                
                <tr>
     <td class="factor_rows_tdb" colspan="2">
شرح بستانکاری :

                    </td>
                    <td class="factor_rows_tdb" colspan="2" style="text-align:left">
بستانکاری :
                    </td>
                    <td class="factor_rows_tdb persian_digits" style="">

ریال
                    </td>
                </tr> -->

<!--                 <tr>
                    <td class="factor_rows_tdb" colspan="2">
شرح بدهکاری :

                    </td>
                    <td class="factor_rows_tdb" colspan="2" style="text-align:left">
بدهکاری :
                    </td>
                    <td class="factor_rows_tdb persian_digits" style="">
        
ریال
                    </td>
                </tr>
 -->
                <tr>
                    <td colspan="5" class="factor_footer_td">
                    </td>
                </tr>

                <tr>
                    <td class="factor_rows_tdb persian_digits" colspan="2">
شرح فاکتور:
                    </td>
                    <td class="factor_rows_tdb" colspan="2" style="text-align:left">
قابل پرداخت :
                    </td>
                    <td class="factor_rows_tdb persian_digits" style="">
                    <?php 
                        if($discount_percent)
                            echo number_format($total_with_discount);
                        else
                            echo number_format($total);
                     ?>
ریال
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="factor_footer_td">
        
                    </td>
                </tr>
                </tbody>
</table>