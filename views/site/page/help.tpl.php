<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Help");
$fork->startBlockSet('body');

?>

<td class="left">

<div class="help">





<h3>How do I join a project?</h3>

<p>Actually, there's no need! Pipeline works a bit differently from most project management tools. It's designed to maximize participation and minimize time spent waiting for permission. Instead of joining a project, you can often jump right in and start contributing. For example, any logged-in user can work on any open task or leave feedback on any contribution. Only a few things, like leading tasks, are reserved for organizers.</p>

<p>If you're not sure yet how (or if) you want to contribute, you can "follow" a project and its updates will appear on your dashboard. If something catches your eye, you can usually contribute immediately.</p>

<div class="line"> </div>


<h3>What do the different user roles mean?</h3>

<p><em>Followers</em> are users who are interested in a project, but haven't committed to it yet. Following a project causes its recent activity to appear on your dashboard.</p>

<p><em>Contributors</em> are the heart and soul of the project: the people who create the content. A contributor is anyone who has joined and/or contributed to a task. Contributors can make new contributions and edit their existing ones.</p>

<p><em>Organizers</em> are responsible for organizing the project. Organizers can create, edit, or lead any task. They can ban/unban users and promote/demote other organizers. They can also edit the project basics.</p>

<p>The <em>creator</em> is the person who started a project. Creators are similar to organizers except that the creator can never be demoted or banned.</p>

<div class="line"> </div>

<h3>How do I make someone an organizer?</h3>

<p>First, go to the People tab for your project.</p>

<p>If the user is already a follower or contributor, you can make him/her an organizer instantly. Click the "Edit Followers" or "Edit Contributors" button (whichever matches this user). Then click the "Make Organizer" button next to the user you want to make an organizer.</p>

<p>If the user is not already part of the project, you will have to invite him/her to be an organizer. Click the "Invite Organizers" button and type in the username of the person you want to be an organizer. (To invite multiple people, separate each username with a comma.) Click the "Invite" button to send the invites. If the user accepts your invitation, he/she will immediately be made an organizer of the project.</p>

<div class="line"> </div>

<h3>What's the difference between tasks and contributions?</h3>

<p>A <em>task</em> is a job or bit of work that needs to be done within a project.</p>

<p>A <em>contribution</em> is someone's work on a task.</p>

<p>Every task has one <em>leader</em> and one or more <em>contributors</em>. Only organizers or the creator can lead a task, but any logged-in user can contribute to a task.</p>

<div class="line"> </div>

<h3>How do I lead a task?</h3>

<p>Only organizers can be task leaders. If you are an organizer, you can assign any organizer (including yourself) to a new task. To change a task's leader, just edit the task and type a different user into the "Leader" field.</p>

<div class="line"> </div>

<h3>How do I contribute to a task?</h3>

<p>Any logged-in user can contribute to a task. First, you need to join the task. Find an open task and join it by clicking the "Join" button.</p>

<p>When you're ready to contribute, click the "Contribute" button on the task page and fill in the information. Click "Create Contribution" and you're done!</p>

<p>You can always revise your contribution by clicking the "Contribute" button again and posting a new contribution.</p>

<div class="line"> </div>

<h3>Which upload formats are supported?</h3>

<p>Currently, Pipeline supports video (.mov, .mpg, .avi), audio (.mp3), Flash animation (.swf), and Flash video (.flv).</p>

<div class="line"> </div>


<h3>What's the difference between comments and discussions?</h3>

<p>Posts on tasks or contributions are called <em>comments</em>. Comments are best for leaving feedback on a specific task or contribution.</p>

<p>Pipeline also supports forum-style posts called <em>discussions</em>. Discussions are best for higher-level conversations that go beyond one task or contribution. Discussions can be categorized to appear in a certain tab (e.g. a discussion about inviting users could be categorized "People") or left uncategorized for project-level discussions.</p>

<div class="line"> </div>


<h3>What do the different project statuses mean?</h3>

<p>They can mean whatever you want. Here is one suggestion:</p>

<ul>
	<li><em>pre-production</em> means you are planning the project: setting up the basics, creating the first tasks, and inviting users.</li>
	<li><em>in production</em> means work has begun. Tasks have been assigned leaders and people are starting to make contributions.</li>
	<li><em>post-production</em> means most contributions are in and most tasks are closed. The final product is being assembled.</li>
	<li><em>finished</em> means the project has been completed and there's nothing left to do.</li>
	<li><em>canceled</em> means the project was abandoned and won't be completed.</li>
</ul>

<div class="line"> </div>


<h3>How do I edit my profile?</h3>

<p>Log in and click your username at the top. Your profile page will load. Click the "Edit" button at the top of the "Profile" box.</p>

<div class="line"> </div>

<h3>How do I stop receiving Pipeline emails?</h3>

<p>You can customize which notification emails to receive (if any) by logging in and clicking the "Settings" link at the top.</p>



</div>

</td>

<td class="extra"> </td>

<td class="right"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');