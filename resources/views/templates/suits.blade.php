<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monochrome Professional </title>
    <style>
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            /* background: #fce4ec;  */
            display: flex;
            justify-content: center;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 15px;
            border-bottom: 2px solid white;
            padding-bottom: 5px;
        }

        .sidebar p, .sidebar ul {
            font-size: 14px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .main-content {
            padding: 30px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 5px;
            color: #222;
        }

        .title {
            font-size: 18px;
            color: #555;
        }

        .contact {
            font-size: 14px;
            margin-top: 10px;
            color: #333;
        }

        h2 {
            font-size: 22px;
            margin-top: 20px;
            border-bottom: 2px solid #222;
            padding-bottom: 5px;
        }

        .job, .education {
            margin-top: 0.5%;
        }

        h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #222;
        }

        .company {
            font-size: 14px;
            font-weight: bold;
            color: #666;
        }

        ul {
            margin-top: 5px;
            padding-left: 20px;
        }

    .resume {
        width: 900px;
        background: white;
        display: table;
        table-layout: fixed;
        width: 100%;
    }

    .sidebar {
        background: #222;
        color: white;
        padding: 30px;
        display: table-cell;
        width: 34%;
        vertical-align: top;
    }

    .main-content {
        padding: 30px;
        display: table-cell;
        width: 70%; 
        vertical-align: top;
    }
</style>
</head>
<body>
    <div class="resume">
        <div class="sidebar">
            <h2>Contact</h2>
            <p>{{ $personalInfo['physicalAddress'] }}</p>
            <p>{{ $personalInfo['email'] }}</p>
            <p>{{ $personalInfo['phoneNo'] }}</p>

            <h2>Skills</h2>
            <ul>
                @foreach ($skills as $skill)
                    <li>{{ $skill }}</li>
                @endforeach
            </ul>

            <h2>Certifications</h2>
            <ul>
                @foreach ($educationExperiences as $educationExperience)
                    <li>{{ $educationExperience['qualification'] }}</li>
                @endforeach
            </ul>
        </div>

        <div class="main-content">
        <header>
                <h1>{{ $personalInfo['fname'] }} {{ $personalInfo['otherNames'] }}</h1>
            </header>

            <section class="summary">
                <h2>Profile</h2>
                <p>{{ $personalInfo['personalStatement'] }}.</p>
            </section>

            <section class="experience">
                <h2>Work Experience</h2>
                @foreach ($workExperiences as $workExperience)
                    <div class="job">
                        <h3>{{ $workExperience['designation'] }}</h3>
                        <p class="company">
                            {{ $workExperience['organization'] }} | {{ $workExperience['dateJoined'] }} - {{ $workExperience['dateLeft'] ? $workExperience['dateLeft'] : 'Present' }}
                        </p>
                        <ul>
                        @foreach ($workExperiences[$loop->index]['achievementsResponsibilities'] as $achievementResponsibility)
                                <li>{{ $achievementResponsibility }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </section>

            <section class="education">
                <h2>Education</h2>
                @foreach ($educationExperiences as $educationExperience)
                    <p><strong>{{ $educationExperience['qualification'] }}</strong></p>
                    <p>{{ $educationExperience['institution'] }}, {{ $educationExperience['dateJoined'] }} - {{ $educationExperience['dateLeft'] ? $educationExperience['dateLeft'] : 'Present' }}</p>
                @endforeach
                
            </section>
        </div>
    </div>
</body>
</html>