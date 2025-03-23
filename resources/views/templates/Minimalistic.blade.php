<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive-San-serif</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            /* Light gray background */
            display: flex;
            justify-content: center;
        }

        .resume {
            width: 800px;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        header {
            text-align: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #333;
        }

        .contact {
            font-size: 14px;
            color: #666;
        }

        .section {
            margin-top: 20px;
        }

        h2 {
            font-size: 20px;
            border-bottom: 2px solid #222;
            padding-bottom: 5px;
            color: #222;
        }

        .skills ul,
        .activities ul,
        .education ul {
            list-style: none;
            padding: 0;
        }

        .skills ul li,
        .interests ul li,
        .education ul li {
            background: #e9ecef;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .experience p {
            font-size: 14px;
            color: #555;
        }

        .job-title {
            font-weight: bold;
            color: #333;
        }

        .school-name {
            font-weight: bold;
            color: #444;
        }
    </style>
</head>

<body>
    <div class="resume">
        <div class="main-content">
            <header>
                <h1 style="font-family: cursive;">{{ $personalInfo['fname'] }} {{ $personalInfo['otherNames'] }}</h1>
            </header>

            <section class="section skills">
                <h2>Key Skills</h2>
                <ul>
                    @foreach ($skills as $skill)
                    <li>{{ $skill }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="section experience">
                <h2>Work & Volunteer Experience</h2>
                @foreach ($workExperiences as $workExperience)
                <div class="job">
                    <h3>{{ $workExperience['designation'] }}</h3>
                    <p class="company">
                        {{ $workExperience['organization'] }} | {{ $workExperience['dateJoined'] }} - {{ $workExperience['dateLeft'] ? $workExperience['dateLeft'] : 'Present' }}
                    </p>
                    <ul>
                        @foreach ($workExperiences[0]['achievementsResponsibilities'] as $achievementResponsibility)
                        <li>{{ $achievementResponsibility }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </section>

            <section class="section education">
                <h2>Education</h2>
                @foreach ($educationExperiences as $educationExperience)
                <p><strong>{{ $educationExperience['qualification'] }}</strong></p>
                <p>{{ $educationExperience['institution'] }}, {{ $educationExperience['dateJoined'] }} - {{ $educationExperience['dateLeft'] ? $educationExperience['dateLeft'] : 'Present' }}</p>
                @endforeach
            </section>

            <section class="section interests">
                <h2>Extracurricular Activities</h2>
                <ul>
                    @foreach ($Interests as $interest)
                    <li>{{ $interest }}</li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</body>

</html>