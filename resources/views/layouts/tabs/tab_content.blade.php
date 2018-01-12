<section>
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'contact')">Contact</button>
        <button class="tablinks" onclick="openTab(event, 'overview-owners')">Overview - Owners and Operators</button>
        <button class="tablinks" onclick="openTab(event, 'overview-guards')">Overview - Security Guards</button>

        @if(isset($loggedIn))
            <button class="tablinks" onclick="openTab(event, 'setup')">Setup</button>
            <button class="tablinks" onclick="openTab(event, 'usage')">Usage</button>
        @endif
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="contact" class="tabcontent padding-top padding-side-lg">
        <div class="content-app title-content-alt-app">
    {{--<div id="contact" class="tabcontent">--}}

        {{--<div class="content-app title-content-alt-app">--}}
            <h3 class="dark-blue-font">Questions, Suggestions, Feedbacks, Concerns?</h3>
            <p class="padding-top">We are happy to hear from you</p>
            <p>Email us at: <span class="text-bold">{{Config::get('constants.COMPANY_EMAIL')}}</span></p>
        </div>
    </div>

    <div id="overview-owners" class="tabcontent padding-top padding-side-lg">
        <div class="content-app title-content-alt-app">
    {{--<div id="overview-owners" class="tabcontent padding-top">--}}
        {{--<div class="content-app title-content-alt-app">--}}
            <h3 class="dark-blue-font">Overview and Features for Owners and Operators of Security Guard
                Companies</h3>

            <p class="padding-top text-bold">
                Step One – Create your business.
            </p>
            <p>
                Enter your company name and contact details you can quickly
                    set up your organisation.
                </p>

            <p class="text-bold">
                Step Two – Add security guards.
            </p>
            <p>
                Enter the name and email address of each security guard.
                    Each guard will then receive an automated email to download the Odin Lite app from either the Apple
                    or Android app store.
                </p>

            <p class="text-bold">
                Step Three – Add guarding locations.
            </p>
            <p>
                Enter the address of each of the locations where a security
                    guard
                    is required to attend. A visual map shows the correct location.
                </p>

            <p class="text-bold">
                Step Four – Create shifts.
            </p>
            <p>
                    Choose a date range, the locations to be guarded, the number of visits
                    required by a guard at each location, and the guard/s responsible. The security guards will receive
                    this information via their Odin Lite app.
                </p>

            <p class="text-bold">
                Step Five – Monitor progress.

            </p>
            <p>Via the Management Console, view the location of security guards
                    whilst they are on duty.
            </p>

            <p class="text-bold">
                Step Six – Generate custom reports.
            </p>
            <p>
                Create reports based on date ranges and/or locations.
                    These reports show dates and times for security guarding activities. They show Case ID’s
                    (and photos where taken) for any investigative activity. Update Case ID’s where new information
                    has come to light. Your clients will appreciate this accurate record of activity for their location.
            </p>

        </div>
    </div>

    <div id="overview-guards" class="tabcontent padding-top padding-side-lg">
        <div class="content-app title-content-alt-app">

            <h3 class="dark-blue-font">Overview and Features for Security Guards</h3>

            <p class="padding-top text-bold">
                Step One – Provide your employer with your email address.
            </p>
            <p>
                This allows them to set you up in their system.
            </p>

            <p class="text-bold">
                Step Two – Download the Odin Lite app.
            </p>
            <p>
                You will receive an email from your employer with instructions on
                how to download the Odin Lite app from either the Apple or Android app store (this is a free download).
                Upon first log in you will be prompted to create a fresh password.
            </p>

            <p class="text-bold">
                Step Three – View your shifts.
            </p>
            <p>
                Your shift dates, times and locations are listed in order allowing you
                to manage your time and prepare for duty.
            </p>

            <p class="text-bold">
                Step Four – Start shift.
            </p>
            <p>
                At the beginning of each shift you will be prompted by the app. You will see
                the number of locations which require guarding duties and the number of times you have to visit
                each location. A map will show you directions to each location.
            </p>

            <p class="text-bold">
                Step Five – Start recording your activity.
            </p>
            <p>
                At each location you can ‘tap in’ when you arrive and ‘tap
                out’ when you leave via the app. You can create case notes and take photos of your activity as required.
            </p>

            <p class="text-bold">
                Step Six – Finish shift.
            </p>
            <p>
                Complete your shift via the app with the knowledge all your activity has been
                recorded. Case notes have their own ID assigned allowing you to update information at a later date if
                necessary. View upcoming shifts and locations.
            </p>
        </div>
    </div>

    @if(isset($loggedIn))
        <div id="setup" class="tabcontent padding-top padding-side-lg">
            <div class="content-app title-content-alt-app">
        {{--<div id="setup" class="tabcontent padding-top padding-side-lg">--}}
            {{--<div class="content-app title-content-alt-app">--}}

                <h3 class="dark-blue-font">Setup</h3>

                <p class="padding-top text-bold">
                    Step One – Register your company.
                </p>
                <p>
                    This simple registration process allows you to create your company
                    profile. It is a simple, yet intuitive process.
                </p>

                <p class="text-bold">Step Two – Add employees.
                </p>
                <p>
                    Enter the biographical details and email address for each of your
                    employees.
                    They will then automatically receive an email prompting them to download the Odin Lite app from
                    either the Apple or Android app store (this is a free download).
                </p>

                <p class="text-bold">Step Three – Add locations.
                </p>
                <p>
                    Add the address of each location which requires guarding activity.
                    Additional notes or instructions pertinent to each location can also be added. Such notes and
                    instructions are provided to the guard via the app.
                </p>

                <p class="text-bold">Step Four – Create a roster.
                </p>
                <p>
                    Add shift details based on date/times, location/s and guard/s.
                </p>
            </div>
        </div>

        <div id="usage" class="tabcontent padding-top padding-side-lg">
            <div class="content-app title-content-alt-app">
        {{--<div id="usage" class="tabcontent padding-top">--}}
            {{--<div class="content-app title-content-alt-app">--}}

                <h3 class="dark-blue-font">Usage</h3>

                <p class="padding-top text-bold">
                    Step One – Create reports.
                </p>
                    <p>
                    Create customized reporting based on parameters including date range and
                    location.
                </p>

                <p class="text-bold">
                    Step Two – Update cases.
                </p>
                <p>
                    Each guarding activity creates an individual case note and case ID.
                    These can be updated as required, such as when additional information needs to be added.
                    (Updating Feature will be available in v2)
                </p>

                <p class="text-bold">
                    Step Three – Send reports.
                </p>
                <p>
                    Send clients a list of activity arising from their location. This includes
                    date/times of guarding activity and case notes and photos.
                </p>

                <p class="text-bold">
                    Step Three – Management.
                </p>
                <p>
                    Add, edit or delete location or guard profiles.
                </p>
            </div>
        </div>
    @endif
</section>