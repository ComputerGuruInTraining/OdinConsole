{{--check the speed of loading using this layout, same same when using this and when not, 1 test; with both pdf styles and pdf layout about 21


--}}
<style>
    .report-title {
        /*color: #4d2970;*/
        margin-left: 5px;
    }

    #report-heading{
        font-family: Futura, "Trebuchet MS", Arial, sans-serif;
        font-size: x-large;
    }

    .report-header{
        padding-left: 15px;
        font-size: large;
    }

    .grey-larger{
        color: #777;
        font-size: 18px;
    }

    #report-date{
        color: #777;
    }

    .margin-table {
        margin-bottom: 25px !important;
    }

    /*Fonts Large*/
    .table > tbody > tr > td,
    .table >tbody >tr > th,
    body
    {
        font-size: large !important;
        text-align: left;
    }

    td, tr{
        height: 18px;
    }

    .table > tbody > tr > th,
    .table > tbody > tr > td{
        padding: 8px;

    }

    .table > tbody > tr > th{
        border-top: 1px solid #f4f4f4;
        border-bottom: 1px solid #f4f4f4;

    }
    .table td {
        border-bottom: 1px solid #f4f4f4;
    }

    .table-responsive > .table tr th:last-child,
    .table-responsive > .table tr td:last-child {
        border-right: 1px solid #f4f4f4;
    }

    .table-responsive > .table tr th:first-child,
    .table-responsive > .table tr td:first-child {
        border-left: 1px solid #f4f4f4;
    }

    /*   Following styles sourced from
        <link rel="stylesheet" href="{{ asset("/bower_components/adminlte/bootstrap/css/bootstrap.min.css") }}" type="text/css">
        */

    .col-md-12  {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .table-responsive {
        overflow: auto;
    }

    .table-responsive > .table tr th,
    .table-responsive > .table tr td {
        white-space: normal !important;
    }

    .table-responsive {
        min-height: .01%;
        overflow-x: auto;
    }

    .table-report, table>tr>td.table-report{
        padding: -15px -20px -15px -10px;
    }

    @media screen and (max-width: 767px) {
        .table-responsive {
            width: 100%;
            margin-bottom: 15px;
            overflow-y: hidden;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive > .table {
            margin-bottom: 0;
        }

        .table-responsive > .table > thead > tr > th,
        .table-responsive > .table > tbody > tr > th,
        .table-responsive > .table > tfoot > tr > th,
        .table-responsive > .table > thead > tr > td,
        .table-responsive > .table > tbody > tr > td,
        .table-responsive > .table > tfoot > tr > td {
            white-space: nowrap;
        }
    }
    @media (min-width: 992px) {
        .col-md-12 {
            float: left;
        }

        .col-md-12 {
            width: 100%;
        }
    }

    /*override user agent style*/
    table {
        border-collapse: separate;
        border-spacing: 0px;
        -webkit-border-horizontal-spacing: 0px;
        -webkit-border-vertical-spacing: 0px;
    }

</style>