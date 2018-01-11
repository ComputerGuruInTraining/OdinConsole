<section>
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'contact')">Contact</button>
        <button class="tablinks" onclick="openTab(event, 'overview-owners')">Overview - Owners and Operators</button>
        <button class="tablinks" onclick="openTab(event, 'overview-guards')">Overview - Security Guards</button>

        @if(isset($loggedIn))
            <button class="tablinks" onclick="openTab(event, 'setup')">Set Up</button>
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

    <div id="contact" class="tabcontent col-md-12">

        <div class="content-app title-content-alt-app">
            <p class="heading-app">Questions, Suggestions, Feedbacks, Concerns?</p>
            <p class="text-app">We are happy to hear from you</p>
            <p>Email us at: <span class="text-bold">{{Config::get('constants.COMPANY_EMAIL')}}</span></p>
        </div>
    </div>
    <div id="overview-owners" class="tabcontent padding-top">
        <div class="content-app title-content-alt-app">
            <h5 class="text-bold">Overview and Features for Owners and Operators of Security Guard
                Companies</h5>

            <ul>
                <li>Step One – Create your business. Enter your company name and contact details you can quickly
                    set up your organisation.
                </li>

                <li>Step Two – Add security guards. Enter the name and email address of each security guard.
                    Each guard will then receive an automated email to download the Odin Lite app from either the Apple
                    or Android app store.
                </li>

                <li>Step Three – Add guarding locations. Enter the address of each of the locations where a security
                    guard
                    is required to attend. A visual map shows the correct location.
                </li>

                <li>Step Four – Create shifts. Choose a date range, the locations to be guarded, the number of visits
                    required by a guard at each location, and the guard/s responsible. The security guards will receive
                    this information via their Odin Lite app.
                </li>

                <li>Step Five – Monitor progress. Via the Management Console, view the location of security guards
                    whilst they are on duty.
                </li>

                <li>Step Six – Generate custom reports. Create reports based on date ranges and/or locations.
                    These reports show dates and times for security guarding activities. They show Case ID’s
                    (and photos where taken) for any investigative activity. Update Case ID’s where new information
                    has come to light. Your clients will appreciate this accurate record of activity for their location.
                </li>
            </ul>
        </div>
    </div>
    <div id="overview-guards" class="tabcontent padding-top">
        <div class="content-app title-content-alt-app">

            <h4>Overview and Features for Security Guards</h4>

            <p>Step One – Provide your employer with your email address. This allows them to set you up in their system.
            </p>

            <p>Step Two – Download the Odin Lite app. You will receive an email from your employer with instructions on
                how to download the Odin Lite app from either the Apple or Android app store (this is a free download).
                Upon first log in you will be prompted to create a fresh password.
            </p>

            <p> Step Three – View your shifts. Your shift dates, times and locations are listed in order allowing you
                to manage your time and prepare for duty.
            </p>

            <p>Step Four – Start shift. At the beginning of each shift you will be prompted by the app. You will see
                the number of locations which require guarding duties and the number of times you have to visit
                each location. A map will show you directions to each location.
            </p>

            <p>Step Five – Start recording your activity. At each location you can ‘tap in’ when you arrive and ‘tap
                out’ when you leave via the app. You can create case notes and take photos of your activity as required.
            </p>

            <p>Step Six – Finish shift. Complete your shift via the app with the knowledge all your activity has been
                recorded. Case notes have their own ID assigned allowing you to update information at a later date if
                necessary. View upcoming shifts and locations.
            </p>
        </div>
    </div>
    @if(isset($loggedIn))
        <div id="setup" class="tabcontent padding-top">
            <div class="content-app title-content-alt-app">

                <h3>Setup</h3>

                <p>Step One – Register your company. This simple registration process allows you to create your company
                    profile. It is a simple, yet intuitive process.
                </p>

                <p>Step Two – Add employees. Enter the biographical details and email address for each of your
                    employees.
                    They will then automatically receive an email prompting them to download the Odin Lite app from
                    either the Apple or Android app store (this is a free download).
                </p>

                <p>Step Three – Add locations. Add the address of each location which requires guarding activity.
                    Additional notes or instructions pertinent to each location can also be added. Such notes and
                    instructions are provided to the guard via the app.
                </p>

                <p>Step Four – Create a roster. Add shift details based on date/times, location/s and guard/s.
                </p>
            </div>
        </div>
        <div id="usage" class="tabcontent padding-top">
            <div class="content-app title-content-alt-app">

                <h2>Usage</h2>

                <p>Step One – Create reports. Create customized reporting based on parameters including date range and
                    location.
                </p>

                <p>Step Two – Update cases. Each guarding activity creates an individual case note and case ID.
                    These can be update , such as when additional information needs to be added.
                </p>

                <p>Step Three – Send reports. Send clients a list of activity arising from their location. This includes
                    date/times of guarding activity and case notes and photos.
                </p>

                <p>Step Three – Management. Add, edit or delete location or guard profiles.
                </p>
            </div>
        </div>
    @endif
</section>