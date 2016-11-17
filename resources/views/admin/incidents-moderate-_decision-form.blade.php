<div class="moderate-history">
    @if($incident->moderation_decisions->count() > 0)
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-1">
                <h3>Moderation History</h3>

                <ul>
                    @foreach($incident->moderation_decisions->sortByDesc('created_at') as $decision)
                        <li>
                            @if($decision->approved)
                                <span class="inline-success bold">Approved</span>
                            @else
                                <span class="inline-danger bold">Rejected</span>
                            @endif

                            by {{ $decision->user->name }} {{ Carbon\Carbon::parse($decision->created_at)->diffForHumans() }}

                            @if($decision->comment)
                                <div class="bottom-margin-sm">
                                    {{ $decision->comment }}
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="form-group top-margin-lg">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="has-success">
                <div class="radio">
                    <label>
                        <input name="approved" type="radio" id="approved" value="1"
                            @if(old('approved') == 1 || $incident->approved)
                                checked
                            @endif
                        >
                        Approve this incident
                    </label>
                </div>
            </div>
            <div class="has-error">
                <div class="radio">
                    <label>
                        <input name="approved" type="radio" id="disapproved" value="0"
                            @if((!is_null(old('approved')) && old('approved') == 0) || (!is_null($incident->approved) && $incident->approved == 0))
                                checked
                            @endif
                        >
                        Disapprove this incident
                    </label>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group field">
        <div class="col-sm-10 col-sm-offset-1">
            <textarea class="form-control" name="moderation_comment" placeholder="Comment on decision made."></textarea>
        </div>
    </div>

</div>