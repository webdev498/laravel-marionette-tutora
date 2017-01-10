
<table style="width:50%">
	<tr>
	    <td>Search Count</td>
	    <td>{{$searchCount}}</td> 
	</tr>
    <tr>
		<td>Lessons</td>
		<td>{{$bookingsCount}}</td>
    </tr>
    <tr>
		<td>Lessons</td>
		<td>{{$bookingsCount}}</td>
    </tr>
    <tr>
		<td>Lesson Value</td>
		<td>{{$bookingsValue}}</td>
    </tr>
    <tr>
		<td>Average Lesson Price</td>
		<td>{{$averageBookingsValue}}</td>
    </tr>
</table>

<h2>Students</h2>
<table>
    <tr>
		<td>New Students</td>
		<td>{{$newStudentsCount}}</td>
    </tr>
    <tr>
		<td>New Student Account Creation Rate</td>
		<td>{{$newStudentEnquiryRate}}</td>
    </tr>
    <tr>
		<td>New Student One Month Conversion Rate</td>
		<td>{{$newStudentOneMonthConversionRate}}</td>
    </tr>
    <tr>
		<td>Four Week Reactivated Student Count</td>
		<td>{{$fourWeekReactivatedStudentCount}}</td>
    </tr>
    <tr>
		<td>Twelve Week Reactivated Student Count</td>
		<td>{{$twelveWeekReactivatedStudentCount}}</td>
    </tr>
</table>
<h2>Relationships</h2>
<table>
    <tr>
    	<td>New Relationships</td>
    	<td>{{$newRelationshipsCount}}</td>
    </tr>
    <tr>
    	<td>Four Week Confirmed Relationships</td>
    	<td>{{$confirmedRelationships}}</td>
    </tr>
    <tr>
    	<td>Four week Confirmed Relationships where Tutora sent first message</td>
    	<td>{{$confirmedByTutora}}</td>
    </tr>
</table>

<h2>Tutors</h2>
<table>
    <tr>
		<td>Live Tutors</td>
		<td>{{$liveTutorsCount}}</td>
    </tr>
    <tr>
		<td>Applications </td>
		<td>{{$tutorApplications}}</td>
    </tr>
    <tr>
		<td>One Month Tutor Live Rate</td>
		<td>{{$oneMonthTutorLiveRate}}</td>
    </tr>
</table>
<h2>Cohort Analysis</h2>
<table>
    @if($threeMonthCohortLessons)
        <tr>
            <td>Three month cohort - average lessons per account</td>
            <td>{{$threeMonthCohortLessons}}</td>
        </tr>
    @endif
    @if($sixMonthCohortLessons)
        <tr>
            <td>Six month cohort - average lessons per account</td>
            <td>{{$sixMonthCohortLessons}}</td>
        </tr>
    @endif

    @if($oneYearCohortLessons)
        <tr>
            <td>One Year month cohort - average lessons per account</td>
            <td>{{$oneYearCohortLessons}}</td>
        </tr>
    @endif
    
</table>

<h2>By Subject</h2>
<table>

    <tr>
		<td>Maths Lessons</td>
		<td>{{$mathsLessons}}</td>
    </tr>
    <tr>
		<td>English</td>
		<td>{{$englishLessons}}</td>
    </tr>
    <tr>
		<td>Science</td>
		<td>{{$scienceLessons}}</td>
    </tr>
    <tr>
		<td>Languages</td>
		<td>{{$languagesLessons}}</td>
    </tr>
    <tr>
		<td>Humanities</td>
		<td>{{$humanitiesLessons}}</td>
    </tr>
    <tr>
		<td>Business</td>
		<td>{{$businessLessons}}</td>
    </tr>
    <tr>
		<td>Computing</td>
		<td>{{$computingLessons}}</td>
    </tr>
    <tr>
		<td>Music</td>
		<td>{{$musicLessons}}</td>
    </tr>
    <tr>
		<td>Admissions</td>
		<td>{{$admissionsLessons}}</td>
    </tr>
    <tr>
		<td>Sports</td>
		<td>{{$sportsLessons}}</td>
    </tr>

</table>