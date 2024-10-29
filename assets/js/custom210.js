(function ($) {
    $(document).ready(function () {
        $('.loader').hide();
        $("#abrestan_login_btn").click(function () {
            $('.loader').show();
            $(this).attr('disabled', 'disabled');
            var abrestan_username=$("input[name=username]").val();
            var abrestan_password=$("input[name=password]").val();
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_login_action',
                'abrestan_username':abrestan_username,
                'abrestan_password':abrestan_password,
            };
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if (response.code===200){
                        $("input[name=username]").each(function (){
                            $(this).val('')
                            $(this).attr('disabled', 'disabled');
                        })
                        $("input[name=password]").each(function (){
                            $(this).val('')
                            $(this).attr('disabled', 'disabled');
                        })
                        $("#abrestan_login_btn").each(function (){
                            $(this).removeAttr('disabled', 'disabled');
                            $(this).hide();
                        })
                        $("#abrestan_logout_btn").show();
                        $('#alert-success-login').each(function (){
                            $(this).removeClass();
                            $(this).addClass(response.class);
                            $(this).show();
                            $(this).children("p").text(response.message);
                        })
                        $(".loader").hide();
                        location.reload();
                    }
                    else{
                        $('#alert-success-login').each(function (){
                            $(this).removeClass();
                            $(this).addClass(response.class);
                            $(this).show();
                            $(this).children("p").text(response.message);

                        })
                        $("#abrestan_login_btn").removeAttr('disabled', 'disabled');
                        $(".loader").hide();
                    }




                }
            });


        })
        $("#abrestan_logout_btn").click(function () {
            $('.loader').show();
            $(this).attr('disabled', 'disabled');
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_logout_action',
            };
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {
                    $("input[name=username]").removeAttr('disabled', 'disabled');
                    $("input[name=password]").removeAttr('disabled', 'disabled');
                    $("#abrestan_logout_btn").each(function (){
                        $(this).removeAttr('disabled', 'disabled');
                        $(this).hide();
                    })
                    $("#abrestan_login_btn").show();
                    $(".loader").hide();

                    $('#alert-success-login').each(function (){
                        $(this).removeClass();
                        $(this).addClass(response.class);
                        $(this).show();
                        $(this).children("p").text(response.message);
                    })
                    $('.abrestan_tab_setting').css('display','none')
                }
            });
        })
        $("#abrestan_company_btn").click(function () {
            $('#alert-success-company').each(function (){
                $(this).hide();
            })
            $('.loader').show();
            $(this).attr('disabled', 'disabled');
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_company_action',
                'company_code':$('select[name="company_code"] option:selected').val(),
                'company_name':$('select[name="company_code"] option:selected').text(),

            };

            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {

                    $(".loader").hide();
                    $("#abrestan_company_btn").removeAttr('disabled', 'disabled');
                    $('#alert-success-company').each(function (){
                        $(this).removeClass();
                        $(this).addClass(response.class);
                        $(this).show();
                        $(this).children("p").text(response.message);
                    })
                }
            });

        })
        $("#abrestan_sync_orders_btn").click(function () {
            var order_from_date=$("input[name=from-date]").val();
            var order_to_date=$("input[name=to-date]").val();
            $('.loader').show();
            $(this).attr('disabled', 'disabled');
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_get_orders_action',
                'order_from_date': order_from_date,
                'order_to_date':order_to_date,
            };
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {
                    $('.loader').show();
                    send_order(response)
                }
            });
        })
        $("#abrestan_send_orders_btn").click(function () {
            var loader=$(".loader")
            $('#alert-success-sync_orders').each(function (){
                $(this).removeClass();
                $(this).hide();
                $(this).children("p").text("");
            })
            loader.show();
            var orderID=$("input[name=orders_id]").val();
            $(this).attr('disabled', 'disabled');
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_send_orders_action',
                'orders':[orderID]
            };
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {

                    $('#alert-success-sync_orders').each(function (){
                        $(this).removeClass();
                        $(this).addClass(response.class);
                        $(this).show();
                        $(this).children("p").text(response.message);
                    })
                    loader.hide();
                    $("#abrestan_sync_orders_btn").removeAttr('disabled', 'disabled');
                    location.reload();
                }
            });

        })
        $("#abrestan_setting_btn").click(function () {
            var add_factor=$("select[name=add_factor]").val();
            var delete_factor=$("select[name=delete_factor]").val();
            var category_product=$("input[name=category_product]").val();
            var category_user=$("input[name=category_user]").val();
            var InitialBalance=$("input[name=AddFactorItemsToInitialBalance]").prop('checked');
            var AddCustomerToOrder=$("input[name=AddCustomerToOrder]").prop('checked');
            $('.loader').show();
            $(this).attr('disabled', 'disabled');
            var ajax_url = plugin_ajax_object.ajax_url;
            var data = {
                'action': 'abrestan_save_setting_action',
                'add_factor':add_factor,
                'delete_factor':delete_factor,
                'category_product':category_product,
                'category_user':category_user,
                'InitialBalance':InitialBalance,
                'AddCustomerToOrder':AddCustomerToOrder,
            };
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (response) {

                    $(".loader").hide();
                    $("#abrestan_setting_btn").removeAttr('disabled', 'disabled');
                    $('#alert_success_save_setting').each(function (){
                        $(this).removeClass();
                        $(this).addClass(response.class);
                        $(this).show();
                        $(this).children("p").text(response.message);
                    })
                }
            });


        })
    });
    function send_order(response) {

        if (response==0){
            $(".loader").hide();
            $('#alert-success-sync_orders').each(function (){
                $(this).removeClass();
                $(this).addClass("uk-alert uk-alert-danger");
                $(this).show();
                $(this).children("p").text("سفارشی جهت همگام سازی یافت نشد!");
            });


        }
        else
        {
            var arr_count = response.length / 1;
            for ($i = 0; $i < arr_count; $i++) {

                var part_data = response.splice(0, 1);
                var data = {
                    'action': 'abrestan_send_orders_action',
                    'orders': part_data,
                };


                $.ajax({
                    url: plugin_ajax_object.ajax_url,
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    async: false,
                    success: function (res) {

                    }
                });

            }
        }
        $('#alert-success-sync_orders').each(function (){
            $(this).removeClass();
            $(this).addClass("uk-alert uk-alert-success");
            $(this).show();
            $(this).children("p").text("عملیات با موفقیت انجام شد. جهت کسب اطلاعات بیشتر رخداد هارا بررسی بفرمایید!");
        })
        $(".loader").hide();
        $("#abrestan_sync_orders_btn").removeAttr('disabled', 'disabled');
    }
})(jQuery);
