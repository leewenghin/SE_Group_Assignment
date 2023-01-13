<!-- Modal -->
<div class="modal fade" id="popUpCommonTitleModal" tabindex="-1" aria-labelledby="popUpCommonTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="popUpCommonTitleModalLabel">
                    Please write down a common title to group them:
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="add-common-title" class="col-sm-12">
                    <label for="commonTitle" class="form-label">Common Title</label>
                    <input type="text" class="form-control @error('common_title') is-invalid @enderror" id="commonTitle" name="common_title" value="{{ old('common_title') }}" placeholder="">
                    <div class="invalid-feedback">
                        @error('common_title')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div id="add-to-existing" class="col-12">
                    <label for="commonTitle" class="form-label">Group to exising</label>
                    <select class="form-select @error('group_existing') is-invalid @enderror" id="group_existing" name="group_existing">
                        <option value="">Choose a status...</option>
                        @foreach ($verified_complaints as $vc)
                            <option value="{{ $vc->id }}" @if($vc->id == old('group_existing')) checked @endif>{{ $vc->common_title }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        @error('group_existing')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <hr class="hr" />
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
  </div>

