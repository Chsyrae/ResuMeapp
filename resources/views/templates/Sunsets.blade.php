<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunsets</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            /* background-color: #f0f0f0;  */
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto; 
            border: 10px solid #ff7e5f; 
             /* border-image: linear-gradient(to right, #ff7e5f, #feb47b, #ff6a88, #ffcc70) 1; */
        }

        h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 3em;
            color: black;
            margin: 0;
            text-align: center;
        }

        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 1.8em;
            margin-top: 20px;
            color: black;
        }

        h3 {
            font-family: 'Great Vibes', cursive;
            font-size: 1.5em;
            margin-top: 15px;
            color: #4a4a4a;
        }

        .contact-info {
            margin-bottom: 20px;
            font-size: 1.1em;
            text-align: center;
        }

        .section {
            margin-bottom: 30px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        footer {
            text-align: center;
            font-size: 0.9em;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- <div class="resume"> -->
    <div class="container">
        <div class="main-content">
            <header>
                <h1 style="font-family: cursive;">{{ $personalInfo['fname'] }} {{ $personalInfo['otherNames'] }}</h1>
            </header>

            <div class="contact-info">
                <h2>Contact</h2>
                <p>{{ $personalInfo['physicalAddress'] }}</p>
                <p>{{ $personalInfo['email'] }}</p>
                <p>{{ $personalInfo['contact'] }}</p>
            </div>

            <div class="section">
                <h2>Professional Summary</h2>
                <p>{{ $personalInfo['personalStatement'] }}.</p>
            </div>

            <div class="section">
                <h2>Experience</h2>
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

            </div>
            <div>
                <section class="education">
                    <h2>Education</h2>
                    @foreach ($educationExperiences as $educationExperience)
                    <p><strong>{{ $educationExperience['qualification'] }}</strong></p>
                    <p>{{ $educationExperience['institution'] }}, {{ $educationExperience['dateJoined'] }} - {{ $educationExperience['dateLeft'] ? $educationExperience['dateLeft'] : 'Present' }}</p>
                    @endforeach
                </section>

            </div>
            <section class="skills">
                <h2>Skills</h2>
                <ul>
                    @foreach ($skills as $skill)
                    <li>{{ $skill }}</li>
                    @endforeach
                </ul>
            </section>
        </div>

    </div>
</body>

</html>