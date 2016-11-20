<template>
    <div class="col-sm-12">
        <form method="POST" class="form-horizontal" action="/add" enctype="multipart/form-data" id="incident_form" @submit.prevent="validateForm">
            <input type="hidden" name="_token" id="_token" value="">

            <transition name="fade">
                <div :class="'col-sm-offset-1 col-sm-9 alert alert-' + alert_type" v-if="alert_show">
                    <div class="close-alert pull-right" @click="alert_show = false">
                        <i class="fa fa-lg fa-times"></i>
                    </div>
                    
                    <div v-html="alert_message"></div>
                </div>
            </transition>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" >
                <div class="form-group">
                    <legend :class="{ 'error': errors.title }">Describe the incident in one line</legend>

                    <input type="text" name="title" v-model="title" class="form-control" maxlength="255">

                    <span class="inline-alert" v-show="errors.title">Required</span>
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">
                <div class="form-group datepicker">
                    <legend :class="{ 'error': errors.date }">When did the incident happen?</legend>

                    <input type="hidden" name="date" id="date" value="">
                    <date-picker :date="date" :option="date_options" :limit="date_limit" ></date-picker>
    
                    <span class="inline-alert" v-show="errors.date">An incident date is required</span>

                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">
                <div class="form-group">
                    <legend :class="{ 'error': errors.city || errors.state }">Where did it happen?</legend>

                    <div class="col-sm-3 no-left-padding">
                       <input type="text" name="city" v-model="city" class="form-control" placeholder="City">
                       <span class="inline-alert" v-show="errors.city">Required</span>
                    </div>

                    <div class="col-sm-2  no-left-padding">
                        <select class="form-control state" name="state" v-model="state" v-bind:class="[state == '' ? 'placeholder' : 'selected']">
                            <option value="" hidden disabled>State</option>

                            <option value="AL">AL</option>
                            <option value="AK">AK</option>
                            <option value="AZ">AZ</option>
                            <option value="AR">AR</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DE">DE</option>
                            <option value="DC">DC</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="IA">IA</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="ME">ME</option>
                            <option value="MD">MD</option>
                            <option value="MA">MA</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MS">MS</option>
                            <option value="MO">MO</option>
                            <option value="MT">MT</option>
                            <option value="NE">NE</option>
                            <option value="NV">NV</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NY">NY</option>
                            <option value="NC">NC</option>
                            <option value="ND">ND</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VT">VT</option>
                            <option value="VA">VA</option>
                            <option value="WA">WA</option>
                            <option value="WV">WV</option>
                            <option value="WI">WI</option>
                        </select>
                        <span class="inline-alert" v-show="errors.state">Required</span>

                    </div>

                    <div class="col-sm-7 no-left-padding">
                       <input type="text" name="location_name" v-model="location_name" class="form-control" placeholder="Address or name of location event took place (optional)">
                    </div>

                </div>
                
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">

                <div class="form-group">
                    <legend :class="{ 'error': errors.source || errors.news_article_url || errors.submitter_email || errors.social_media_url || errors.source_other_description }">How do you know about this incident?</legend>

                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="news_article"> News article
                    </label>
                
                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="it_happened_to_me">
                        It happened to me
                    </label>
                
                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="i_witnessed_it">
                        I witnessed it it
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="someone_i_know_witnessed_it">
                        Someone I know witnessed it
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="social_media">
                        Social media
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="source" v-model="source" value="other">
                        Other
                    </label>
                </div>

                <span class="inline-alert" v-show="errors.source">Required</span>

            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_source_other_description">
                <div class="form-group">
                    <input type="text" name="source_other_description" v-model="source_other_description" class="form-control" placeholder="Describe how you know about this incident" maxlength="255">
                    <span class="inline-alert" v-show="errors.source_other_description">Required</span>            
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_email">
                <div class="form-group">
                    <input type="email" name="submitter_email" v-model="submitter_email" class="form-control" placeholder="Your email address" maxlength="255">

                    <div class="top-margin-sm">Sometimes we need additional information to make sure our listings are complete and accurate. We will never share your email address.</div>
                    <span class="inline-alert" v-show="errors.submitter_email">Required</span>
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_news_article_url">
                <div class="form-group">
                    <input type="url" name="news_article_url" v-model="news_article_url" class="form-control" placeholder="URL of the source">
                    <span class="inline-alert" v-show="errors.news_article_url">Required</span>
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_social_media_url">
                <div class="form-group">
                    <input type="url" name="social_media_url" v-model="social_media_url" class="form-control" placeholder="URL of the social media post">
                    <span class="inline-alert" v-show="errors.social_media_url">Required</span>
                </div>
            </div>


            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">

                <div class="form-group">
                    <legend :class="{ 'error': errors.incident_type || errors.other_incident_description }">What kind of incident was it?</legend>

                    <p>Check all that apply</p>

                    <label class="checkbox-inline" for="incident_type_2">
                        <input type="checkbox" name="incident_type" id="incident_type_2" v-model="harassment" value="harassment"> Harassment
                    </label>

                    <label class="checkbox-inline" for="incident_type_3">
                        <input type="checkbox" name="incident_type" id="incident_type_3" v-model="intimidation" value="intimidation"> Intimidation
                    </label>
                
                    <label class="checkbox-inline" for="incident_type_4">
                        <input type="checkbox" name="incident_type" id="incident_type_4" v-model="physical_violence" value="physical_violence"> Physical Violence
                    </label>

                    <label class="checkbox-inline" for="incident_type_6">
                        <input type="checkbox" name="incident_type" id="incident_type_6" v-model="property_crime" value="property_crime"> Property Crime
                    </label>

                    <label class="checkbox-inline" for="incident_type_5">
                        <input type="checkbox" name="incident_type" id="incident_type_5" v-model="vandalism" value="vandalism"> Vandalism
                    </label>

                    <label class="checkbox-inline" for="incident_type_1">
                        <input type="checkbox" name="incident_type" id="incident_type_1" v-model="verbal_abuse" value="verbal_abuse"> Verbal Abuse
                    </label>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="incident_type" v-model="other" value="other"> Other
                    </label>

                    <div class="inline-alert" v-show="errors.incident_type">Required</div>

                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_other">
                <div class="form-group">
                    <input type="text" name="other_incident_description" v-model="other_incident_description" class="form-control" placeholder="How would you classify the incident?" maxlength="255">
                    <span class="inline-alert" v-show="errors.other_incident_description">Required</span>
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md" v-if="show_photo_upload">
                <div class="form-group">
                    <input type="file" name="photo" @change="onFileChange">
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">
                <div class="form-group">
                    <legend :class="{ 'error': errors.description }">Describe the incident</legend>
                    <textarea class="form-control description" v-model="description" name="description"></textarea>
                    <span class="inline-alert" v-show="errors.description">Required</span>
                </div>
            </div>

            <div class="col-sm-offset-1 col-sm-9 bottom-margin-md">
                <div class="form-group">
                    <div class="g-recaptcha" id="recaptcha" data-sitekey="6LcZbQwUAAAAAF5wPG9wMWITftEoIXuf0HyomVOE"></div>
                    <span class="inline-alert" v-show="errors.g_recaptcha_response">Verify you are not a robot.</span>            
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <button type="submit" id="submit-button" class="btn btn-success" @click="validateForm($event)"><div v-html="document_submit_html"></div></button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    export default {
        props: {
            'data-sitekey': '6LcZbQwUAAAAAF5wPG9wMWITftEoIXuf0HyomVOE',
        },
        computed: {
            incident_type_checked() {

                if(this.harassment ||
                    this.intimidation  ||
                    this.physical_violence ||
                    this.property_crime ||
                    this.vandalism ||
                    this.verbal_abuse ||
                    this.other                  
                ) { 
                    return true;
                }

                return false;
            },
            show_news_article_url() {
                if(this.source == 'news_article') {
                    return true;
                }

                return false;
            },
            show_email() {
                if(this.source != 'news_article' && this.source != 'social_media' && this.source != '') {
                    return true;
                }

                return false;
            },
            show_source_other_description() {
                if(this.source == 'other') {
                    return true;
                }

                return false;
            },
            show_photo_upload() {
                if(this.harassment == true || 
                    this.intimidation == true ||
                    this.physical_violence == true ||
                    this.property_crime == true ||
                    this.vandalism == true ||
                    this.other == true
                ) {
                    return true;
                }
                return false;
            },
            show_other() {
                if(this.other == true) {
                    return true;
                }
                return false;
            },
            show_social_media_url() {
                if(this.source == 'social_media') {
                    return true;
                }

                return false;
            },
            show_state() {
                if(this.city != '') {
                    return true;
                }
                return false;
            },
        },
        methods: {
            addIncident() {

                this.document_submit_html = this.document_submit_processing_html;

                var form_data = new FormData();
                form_data.append('title', this.title);
                form_data.append('photo', this.photo);
                form_data.append('date', this.date.time);
                form_data.append('city', this.city);
                form_data.append('state', this.state);
                form_data.append('location_name', this.location_name);
                
                form_data.append('source', this.source);
                form_data.append('source_other_description', this.source_other_description);

                form_data.append('harassment', this.harassment ? true : false);
                form_data.append('intimidation', this.intimidation ? true : false);
                form_data.append('physical_violence', this.physical_violence ? true : false);
                form_data.append('property_crime', this.property_crime ? true : false);
                form_data.append('vandalism', this.vandalism ? true : false);
                form_data.append('verbal_abuse', this.verbal_abuse ? true : false);
                form_data.append('other', this.other ? true : false);
                form_data.append('other_incident_description', this.other ? this.other_incident_description : false);

                form_data.append('news_article_url', this.news_article_url);
                form_data.append('social_media_url', this.social_media_url);
                form_data.append('submitter_email', this.submitter_email);
                form_data.append('photo', this.photo);
                
                form_data.append('description', this.description);
                form_data.append('g-recaptcha-response', this.g_recaptcha_response);

                this.$http.post('/add', form_data).then((response) => {

                    this.document_submit_html = this.document_submit_default_html;


                    if(response.body.status == 'failure') {
                        this.alert_type = 'danger';
                        this.alert_message = this.parseErrorMessage(response.body.errors);
                        this.alert_show = true;

                        window.scrollTo(0, 0);


                        return false;
                    } 

                    this.alert_show = true;
                    this.alert_type = 'success';
                    this.alert_message = response.body.message;

                    this.title = '';
                    this.date = { time: '' };
                    this.city = '';
                    this.location_name = '';
                    this.source = '';
                    this.source_other_description = '';
                    this.news_article_url = '';
                    this.submitter_email = '';
                    this.social_media_url = '';
                    this.harassment = '';
                    this.intimidation = '';
                    this.physical_violence = '';
                    this.property_crime = '';
                    this.vandalism = '';
                    this.verbal_abuse = '';                 
                    this.other = false;
                    this.other_incident_description = '';
                    this.photo = '';
                    this.description = '';
                    window.scrollTo(0, 0);

                });
            },
            onFileChange(e) {
                if (!e.target.files.length){
                    this.photo = '';
                }

                var form_data = new FormData();
                form_data.append('file', e.target.files[0]);

                this.photo = e.target.files[0];
            },
            parseErrorMessage(errors) {
                var error_response = '<h4 class="error"><i class="fa fa-exclamation-triangle"></i> There was a problem.</h4><ul class="ajax-error-bag">';

                if(typeof errors == 'object') {
                    for(var key in errors) {

                        if(errors.hasOwnProperty(key)){
                            error_response += '<li>' + errors[key] + '</li>';
                        }
                    }
                } else {
                    error_response += '<li>' + errors + '</li>';
                }
                error_response +='</ul>';

                return error_response;
            },
            resetErrors() {
                for(var key in this.errors) {
                    this.errors[key] = false;
                }

                this.validates = true;
            },
            validateForm(event) {
                event.preventDefault();

                this.g_recaptcha_response = document.getElementsByName('g-recaptcha-response')[0].value;

                this.resetErrors();

                if(this.title == '') {
                    this.errors.title = true;
                    this.validates = false;
                }

                if(this.date.time == '') {
                    this.errors.date = true;
                    this.validates = false;
                }

                if(this.city == '') {
                    this.errors.city = true;
                    this.validates = false;
                }

                if(this.source == '') {
                    this.errors.source = true;
                    this.validates = false;
                }

                if(this.show_news_article_url && this.news_article_url == '') {
                    this.errors.news_article_url = true;
                    this.validates = false;
                }

                if(this.show_email && this.submitter_email == '') {
                    this.errors.submitter_email = true;
                    this.validates = false;
                }

                if(this.show_social_media_url && this.social_media_url == '') {
                    this.errors.social_media_url = true;
                    this.validates = false;
                }

                if(this.show_source_other_description && this.source_other_description == '') {
                    this.errors.source_other_description = true;
                    this.validates = false;
                }

                if(! this.incident_type_checked) {
                    this.errors.incident_type = true;
                    this.validates = false;
                }

                if(this.show_other && this.other_incident_description == '') {
                    this.errors.other_incident_description = true;
                    this.validates = false;
                }

                if(this.state == '') {
                    this.errors.state = true;
                    this.validates = false;
                }

                if(this.description == '') {
                    this.errors.description = true;
                    this.validates = false;
                }

                if(this.g_recaptcha_response == '') {
                    this.errors.g_recaptcha_response = true;
                    this.validates = false;
                }

                if (! this.validates) {
                    this.alert_type = 'danger';
                    this.alert_message = 'We need some more information. Please see below.';
                    this.alert_show = true;

                    window.scrollTo(0, 0);

                    return false;
                } 

                this.addIncident();
            }
        },
        data() {
            return {
                alert_show: false,
                alert_type: 'danger',
                alert_message: '',
                city: '',
                date: {
                     time: ''
                },
                date_options: {
                    type: 'day',
                    week: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
                    month: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    format: 'MM/DD/YYYY',
                    placeholder: 'mm/dd/yyyy',
                    inputStyle: {
                      'display': 'inline-block',
                      'padding': '6px 12px',
                      'line-height': '1.6',
                      'font-size': '14px',
                      'border': '2px solid #fff',
                      'box-shadow': 'inset 0 1px 1px 0 rgba(0, 0, 0, 0.075)',
                      'border-radius': '4px',
                      'color': '#555555',
                      'border': '1px solid #ccd0d2',
                      'font-family': 'Raleway'
                    },
                    buttons: {
                      ok: 'OK',
                      cancel: 'Cancel'
                    },
                    overlayOpacity: 0.5, // 0.5 as default
                    dismissible: true // as true as default
                },
                date_limit: [{}],
                description: '',
                document_submit_default_html: '<i class="fa fa-plus-circle fa-fw"></i> Document It!',
                document_submit_processing_html: '<i class="fa fa-spinner fa-spin fa-fw"></i> Submitting',
                document_submit_html: '<i class="fa fa-plus-circle fa-fw"></i> Document It!',
                errors: {
                    title: false,
                    date: false,
                    city: false,
                    state: false,
                    source: false,
                    news_article_url: false,
                    submitter_email: false,
                    social_media_url: false,
                    source_other_description: false,
                    incident_type: false,
                    other_incident_description: false,
                    description: false,
                    g_recaptcha_response: false,
                },
                harassment: false,
                g_recaptcha_response: '',
                source: '',
                source_other_description: '',
                intimidation: false,
                location_name: '',
                other_incident_description: '',
                other: false,
                photo: '',
                physical_violence: false,
                property_crime: false,
                social_media_url: '',
                news_article_url: '',
                state: '',
                submitter_email: '',
                title: '',
                validates: true,
                vandalism: false,
                verbal_abuse: false,
            }
        },    
    }

</script>