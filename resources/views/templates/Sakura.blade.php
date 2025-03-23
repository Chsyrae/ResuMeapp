<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakura</title>
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
            color:rgb(17, 10, 12);
            font-family: fantasy;
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
            border-bottom: 2px solid #e91e63;
            padding-bottom: 5px;
        }
        .section-title {
            color: black; 
           border-bottom: 2px solid #f5f5dc; 
          padding-bottom: 5px; 
          margin-bottom: 10px; 
          }
        .job, .education {
            margin-top: 0.5%;
        }

        h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;;
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
        padding: 20px;
        background-color: #36013f;
        color: #333;
        padding: 30px;
        display: table-cell;
        width: 34%;
        height: 100vh;
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
            <div class="profile-summary">
                <p>{{ $personalInfo['personalStatement'] }}.</p>
                <div class="contact-info">
                    <div class="section-title">Contact</div>
                    <p>{{ $personalInfo['physicalAddress'] }}</p>
                    <p>{{ $personalInfo['email'] }}</p>
                    <p>{{ $personalInfo['contact'] }}</p>
                </div>
                <div class="skills">
                    <div class="section-title">Skills</div>
                    <ul>
                        @foreach ($skills as $skill)
                        <li>{{ $skill }}</li>
                        <li>{{ $skill }}</li>
                        <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="education">
                    <div class="section-title">Education</div>
                    @foreach ($educationExperiences as $educationExperience)
                    <p><strong>{{ $educationExperience['qualification'] }}</strong></p>
                    <p>{{ $educationExperience['institution'] }}, {{ $educationExperience['dateJoined'] }} - {{ $educationExperience['dateLeft'] ? $educationExperience['dateLeft'] : 'Present' }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="main-content">
            <h1 class="name">{{ $personalInfo['fname'] }} {{ $personalInfo['otherNames'] }}</h1>
            <div class="profession">Profession</div>
            <div class="work-history">
                <div class="section-title">Work History</div>
                @foreach ($workExperiences as $workExperience)
                <div class="work-history-item">
                    <h3 class="work-title">{{ $workExperience['designation'] }}</h3>
                    <p class="company">
                        {{ $workExperience['organization'] }} | {{ $workExperience['dateJoined'] }} - {{ $workExperience['dateLeft'] ? $workExperience['dateLeft'] : 'Present' }}
                    </p>
                    <ul>
                        @foreach ($workExperience['achievementsResponsibilities'] as $achievementResponsibility)
                        <li>{{ $achievementResponsibility }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            <div class="interests">
                <div class="section-title">Interests</div>
                <ul>
                    @foreach ($interests as $interest)
                    <li>{{ $interest }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</body>

</html>