<div class="col-lg-4 col-sm-6 col-12 mb-3">

    {{-- pending = text-bg-warning,
        active = text-bg-info,
        kiv = text-bg-primary,
        completed = text-bg-success--}}
    <?php
        $status_filter = '';
        if($sts['name'] == "Pending") {
            $status_filter = 1;
            $card_bg = "text-bg-warning";
            $sts_desc = "haven't been reviewed by the helpdesk.";
        }
        else if($sts['name'] == "Keep in view") {
            $status_filter = 2;
            $card_bg = "text-bg-primary";
            $sts_desc = "haven't been accepted by the executive.";
        }
        else if($sts['name'] == "Active") {
            $status_filter = 3;
            $card_bg = "text-bg-info";
            $sts_desc = "have been processed by the executive.";
        }
        else if($sts['name'] == "Done") {
            $status_filter = 4;
            $card_bg = "text-bg-secondary";
            $sts_desc = "complaints have been completed by the executive.";
        }
        else if ($sts['name'] == "Reprocessing") {
            $status_filter = 5;
            $card_bg = "text-bg-danger";
            $sts_desc = "complaints have been reprocessing by the helpdesk.";
        }
        else if($sts['name'] == "Closed") {
            $status_filter = 6;
            $card_bg = "text-bg-success";
            $sts_desc = "have been closed by the helpdesk.";
        }
    ?>

    <div class="card ">
        <div class="card-body <?php echo $card_bg ?>">
        <h4 class="card-title ">{{$sts['name']}}</h4>
        <p class="card-text">There are {{$sts['number']}} complaints <?php echo " ".$sts_desc ?></p>
        <a href="{{ route($route, ['status_filter' => $status_filter]) }}" class="btn text-bg-dark ">More Detail</a>
        </div>
    </div>
</div>
