@extends('layouts.default')
@section('content')
<section class="acount_wrapper">
    <div class="profile_wrapper mb30">
        <div class="profile_pan mb33">
            <div class="heading_section">
                <h1 class="h1_heading">Profile</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det"> <img src="{{asset('assets/images/profile_pic.jpg')}}" width="193" height="289" />
                <article class="w60_5p">
                    <h3 class="mb19">Sam Student</h3>
                    <p>1 on 1 Student Tutoring<br />
                        Senior</p>
                    <div class="info_list">
                        <p class="name w152">Gender</p>
                        <p class="det">Male</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w152">Language(s)</p>
                        <p class="det">English (Primary) <br />
                            Spanish</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w152">Course(s)</p>
                        <p class="det">Johnson HS - ACT Class 01<br />
                            Johnson HS - SAT Class 02</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb30">
            <div class="heading_section">
                <h1 class="h1_heading">Permissions</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt13 pb23">
                <article class="w100p">
                    <div class="info_list no-bdr">
                        <p class="name w152">Student</p>
                        <p class="det w430"></p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Alerts</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Set Alerts</p>
                        <p class="det w285">Attendance, Academic, Billing, Performance, Late Work, Classes, Missing Work</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w310">Receive by</p>
                        <p class="det w285">System, Email</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb33">
            <div class="heading_section">
                <h1 class="h1_heading">Tutors</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <div class="heading_section  pL0 pt0 pb9">
                    <p class="name_heading w360">Name</p>
                    <p class="hour_heading w150"><span class="heading_Span">Hours</span> <i class="sm_Arrow mL5 mt9"></i></p>
                    <div class="clr"></div>
                </div>
                <article class="w100p">
                    <div class="info_list no-bdr">
                        <p class="name w360"><a href="#">Sara Abrahms</a></p>
                        <p class="det w150">10</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w360"><a href="#">Christopher Allen</a></p>
                        <p class="det w150">10</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w360"><a href="#">Rebecca Anderson</a></p>
                        <p class="det w150">10</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w360"><a href="#">Jennifer Arrends</a></p>
                        <p class="det w150">10</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr mb10">
                        <p class="name w360"><a href="#">Jennifer Arrends</a></p>
                        <p class="det w150">10</p>
                        <div class="clr"></div>
                    </div>
                    <button type="button" name="edit" class="edit_btn fltL mb15" value="Edit">Load More</button>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Notes</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb14">
                <div class="heading_section  pL0 pt0 pb9">
                    <p class="name_heading w220"><span class="heading_Span">Sender</span> <i class="sm_Arrow mL5 mt9"></i></p>
                    <p class="hour_heading w350">Message</p>
                    <div class="clr"></div>
                </div>
                <article class="w100p">
                    <div class="info_list">
                        <p class="det w220">02/14/2015 10:43AM<br />
                            By: <a href="#">Scott Schneider</a><br />
                            Access Level:3</p>
                        <p class="msg w350">Lorem ipsum dolor sit amet, consectetur adipisci
                            ng elit, sed do eiusmod tempor incididunt ut 
                            labore et dolore magna aliqua...</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr mb22">
                        <p class="det w220">02/14/2015 10:43AM<br />
                            By: <a href="#">Scott Schneider</a><br />
                            Access Level:3</p>
                        <p class="msg w350">Lorem ipsum dolor sit amet, consectetur adipisci
                            ng elit, sed do eiusmod tempor incididunt ut 
                            labore et dolore magna aliqua...</p>
                        <div class="clr"></div>
                    </div>
                    <button type="button" name="edit" class="edit_btn fltL mb15" value="Edit">Load More</button>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Internal Info</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Old CRM ID</p>
                        <p class="det w285">88731</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Client Status</p>
                        <p class="det w285">Active</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Initial Diagnostic Date</p>
                        <p class="det w285">02/14/2015</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Initial Diagnostic Location</p>
                        <p class="det w285">School</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Initial Call Taken By</p>
                        <p class="det w285">Sabeen Shami</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Initial Call Date</p>
                        <p class="det w285">02/14/2015</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Initial Caller</p>
                        <p class="det w285">Mother</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Referral Type</p>
                        <p class="det w285">Returning Client</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Referral Comments</p>
                        <p class="det w285">Sed ut perspiciatis unde omnis is
                            natus error sit volu ptatem accu
                            ntium doloremque laudantium</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Responsible Director</p>
                        <p class="det w285">Sabeen Shami</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">NCM Director</p>
                        <p class="det w285">No NCM</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">NCM Date</p>
                        <p class="det w285"> </p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Start Time</p>
                        <p class="det w285">1:30PM</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w310">Next Step Date</p>
                        <p class="det w285">04/22/2015</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>
    </div>
    <div class="contact_pan">
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Contact</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Phone (Primary)</p>
                        <p class="det w285">88731</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Email (Primary)</p>
                        <p class="det w285">Active</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list  no-bdr">
                        <p class="name w310">Address (Primary)</p>
                        <p class="det w285">02/14/2015</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>

        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Login & Password</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Username</p>
                        <p class="det w285">88731</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list  no-bdr">
                        <p class="name w310">Password</p>
                        <p class="det w285">********</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>

        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Privacy</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list  no-bdr">
                        <p class="name w310">Setting</p>
                        <p class="det w285">Standard</p>
                        <div class="clr"></div>
                    </div>
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Parent</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Name</p>
                        <p class="det w285">John Smith (Father)<br />
                            Julia Smith (Mother)</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Language(s)</p>
                        <p class="det w285">Spanish (Primary) (Father)<br />
                            English (Primary) (Mother)</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Phone (Primary)</p>
                        <p class="det w285">606 - 832 - 2468</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Parent’s Mobile</p>
                        <p class="det w285">606 - 456 - 2345</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Email (Primary)</p>
                        <p class="det w285">jsmith@ibm.com</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w310">Address (Primary)</p>
                        <p class="det w285">124 Hudson Rd<br />
                            Springfield, IL 60672 USA</p>
                        <div class="clr"></div>
                    </div>           
                </article>
                <div class="clr"></div>
            </div>
        </div>


        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Billing</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">Registration Policy</p>
                        <p class="det w285">Received</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Auto Pay</p>
                        <p class="det w285">Yes</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Payment Status</p>
                        <p class="det w285">Approved</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Payment (Primary)</p>
                        <p class="det w285">Approved</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Name</p>
                        <p class="det w285">Credit Card</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Acct Number</p>
                        <p class="det w285">Capital One</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Id Number</p>
                        <p class="det w285">*0012</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Name on Card</p>
                        <p class="det w285">745823</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Exp Date</p>
                        <p class="det w285">John Smith</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w310">Address</p>
                        <p class="det w285">124 Hudson Rd<br />
                            Springfield, IL 60672 USA</p>
                        <div class="clr"></div>
                    </div>           
                </article>
                <div class="clr"></div>
            </div>
        </div>
        <div class="profile_pan mb28">
            <div class="heading_section">
                <h1 class="h1_heading">Additional Student Info</h1>
                <button type="button" name="edit" class="edit_btn" value="Edit">Edit</button>
                <div class="clr"></div>
            </div>
            <div class="profile_Det pt8 pb23">
                <article class="w100p">
                    <div class="info_list">
                        <p class="name w310">School</p>
                        <p class="det w285">Jackson High School</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Graduation Year</p>
                        <p class="det w285">2016 (11)</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Office</p>
                        <p class="det w285">Chicago</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w200">Program<br />
                            <span>ACT</span>
                            <span>Academic</span>
                        </p>
                        <p class="name w240">Target Tests<br />
                            <span>December</span>
                            <span>Fall</span></p>
                        <p class="name w150">Final Test<br />
                            <span>2014</span>
                            <span>2014</span></p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list">
                        <p class="name w310">Name</p>
                        <p class="det w285">Credit Card</p>
                        <div class="clr"></div>
                    </div>
                    <div class="info_list no-bdr">
                        <p class="name w310">Default Tutoring Location</p>
                        <p class="det w285">Office: Chicago</p>
                        <div class="clr"></div>
                    </div>

                </article>
                <div class="clr"></div>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</section>
@endsection