<h3 class="mb-3">Actions History</h3>
<div class="bg-white rounded p-3 border">
    @foreach ($verified_complaint->complaint_loggings->sortBy('created_at') as $complaint_logging)
        <?php
            $title_person_in_charge = "";
            if (in_array($complaint_logging->complaint_action_id, [1,2,6,7,8,9])) {
                $title_person_in_charge = "Helpdesk";
            }
            else if (in_array($complaint_logging->complaint_action_id, [3,4,5])) {
                $title_person_in_charge = "Executive";
            }
        ?>
        <h3>{{ $title_person_in_charge }} Previous Action</h3>

        @if ($title_person_in_charge == "Helpdesk")
            <div class="d-lg-flex justify-content-between d-block">
                @if (in_array($complaint_logging->complaint_action_id, [8,9]))
                    <div class="col-12">
                        <?php
                            $icon = "fa-solid fa-clipboard-check";
                            $is_show_department = "";
                            if ($complaint_logging->complaint_action_id == 8) {
                                $icon = "fa-solid fa-arrows-spin";
                                $is_show_department = ($complaint_logging->assigned_to_department_id != null) ? ' to '.$complaint_logging->department->name : "";
                            }

                        ?>
                        <p class="mb-3">{{ $complaint_logging->complaint_action->name.$is_show_department }} <i class="{{ $icon }} text-success"></i></p>
                    </div>
                @else
                    <div class="col-lg-6 col-12 m-auto">
                        <div class="form-check fs-5 standard_content">
                            <input class="form-check-input" type="radio" value="accept" id="acceptAction-{{ $complaint_logging->id }}" name="acceptAction-{{ $complaint_logging->id }}" @if(in_array($complaint_logging->complaint_action_id, [1,6])) checked @endif disabled>
                            <label class="form-check-label" for="acceptAction-{{ $complaint_logging->id }}">
                                {{ ($complaint_logging->complaint_action_id == 1) ? 'Accepted' : 'Approved' }}
                            </label>
                        </div>
                        <div class="form-check fs-5 standard_content">
                            <input class="form-check-input" type="radio" value="decline" id="DeclineAction-{{ $complaint_logging->id }}" name="DeclineAction-{{ $complaint_logging->id }}" @if(in_array($complaint_logging->complaint_action_id, [2,7])) checked @endif disabled>
                            <label class="form-check-label" for="DeclineAction-{{ $complaint_logging->id }}">
                                {{ ($complaint_logging->complaint_action_id == 2) ? 'Declined' : 'Rejected' }}
                            </label>
                        </div>

                        <div class="col-lg-6 col-12 mt-3">
                            <label for="select_department-{{ $complaint_logging->id }}" class="form-label"> Assign to:</label>
                            <input type="text" id="select_department-{{ $complaint_logging->id }}" class="form-control" value="{{ ($complaint_logging->assigned_to_department_id != null) ? $complaint_logging->department->name : '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 my-3">
                        <label for="executive_remark-{{ $complaint_logging->id }}" class="form-label">Remark</label>
                        <textarea class="form-control" name="executive_remark" id="executive_remark-{{ $complaint_logging->id }}" cols="15" rows="5" disabled>{{ $complaint_logging->remark }}</textarea>
                    </div>
                @endif
            </div>
        @elseif ($title_person_in_charge == "Executive")


            <div class="mt-3 mb-5 d-lg-flex justify-content-between">
                <div class="col-lg-6 col-12">
                    @if (in_array($complaint_logging->complaint_action_id, [3,4,5]))
                        @if ($complaint_logging->complaint_action_id == 3)
                            <p class="mb-3">Task accepted by Executive <i class="fa-solid fa-circle-check text-success"></i></p>
                        @endif
                        <?php
                        $isDeclined = ($complaint_logging->complaint_action_id == 4);
                        $showColor = $isDeclined ? 'danger' : 'success';
                        $wordToShow = $isDeclined ? 'Declined' : (($complaint_logging->complaint_action_id == 3) ? 'Accepted' : 'Done');
                        ?>
                        <div class="fs-5 text-bg-{{ $showColor }} fw-bolder rounde standard_content text-center mb-2">
                            {{ $wordToShow }}
                        </div>

                        <div class="fs-5 text-bg-light p-3 rounded">
                            {{ $complaint_logging->remark }}
                        </div>
                    @endif
                </div>
                @if (($complaint_logging->complaint_action_id == 4 || $complaint_logging->complaint_action_id == 5) && $complaint_logging->processing_document != null && $complaint_logging->processing_document->count() > 0)
                    <div class="col-lg-5 col-12">

                        @if ($complaint_logging->processing_document->file_path != null && $complaint_logging->processing_document->file_name != null)
                            @if (str_starts_with($complaint_logging->processing_document->mime_type, "image/"))
                                <img src="{{ asset($complaint_logging->processing_document->file_path.$complaint_logging->processing_document->file_name) }}" alt="something.jpg" width="100%">
                            @elseif (str_starts_with($complaint_logging->processing_document->mime_type, "video/"))
                                <video id="problem_video" width="100%" height="auto" controls>
                                    <source src="{{ asset($complaint_logging->processing_document->file_path.$complaint_logging->processing_document->file_name) }}" type="{{ $complaint_logging->processing_document->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <i class="fa-regular fa-folder-open"></i> <a href="{{ asset($complaint_logging->processing_document->file_path.$complaint_logging->processing_document->file_name) }}" class="ms-3">Files</a>
                            @endif
                        @endif

                    </div>
                @endif
            </div>
        @endif

        <hr class="hr" />
    @endforeach
</div>
