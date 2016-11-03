<!-- Data Table -->
<script>
    function changeOfficeCode() {
        var office_id = $('#office_id option:selected').val();
        var of_code = '';
        switch (office_id){
            case '1':
                of_code = '000000001';
                break;
            case '2':
                of_code = '000000007';
                break;
            case '3':
                of_code = '000000004';
                break;
            case '4':
                of_code = '000000005';
                break;
            case '5':
                of_code = '000000008';
                break;
            case '6':
                of_code = '000000009';
                break;
            case '7':
                of_code = '000000006';
                break;
            default:
                of_code = '000000001';
                break;
        }
        $('#office_code').val(of_code);
    }
</script>