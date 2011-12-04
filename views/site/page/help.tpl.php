<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Help");
$fork->set('headingURL', Url::help());
$fork->startBlockSet('body');

?>

<td class="left">

<div class="help">


<h3 id="help-bug">What if I notice something is broken?</h3>

<p>Sorry it broke! Pipeline is still in beta and we are actively testing and fixing bugs. If you notice a problem, please <a href="mailto:<?= CONTACT_EMAIL ?>">email us</a> and let us know. Give as much information as possible about what you were doing when the problem occurred, as well as your browser and operating system (including version numbers, if possible). We will file a bug report and try to resolve it as soon as possible.</p>

<div class="line"> </div>

<h3 id="help-feature">What if I have a feature request?</h3>

<p>We're excited to hear what our users want out of Pipeline. We encourage you to <a href="mailto:<?= CONTACT_EMAIL ?>">email us</a> your ideas and feature requests. We can't guarantee we will make it happen, but we will read and consider all ideas.</p>

<div class="line"> </div>

<h3 id="help-roles">What do the different user roles mean?</h3>

<p><em>Members</em> are people who joined a project. They can do most things you'd want to do in a project: join and contribute to tasks, edit their contributions, post comments, and participate in discussions. They can also invite others to join the project.</p>

<p><em>Trusted members</em> are members with access to some project-level abilities. They can create and lead tasks, edit the project settings, and ban/unban users from the project. They can also trust/untrust other members. Trusted members are identified by an asterisk (*) after their usernames.</p>

<p>The <em>creator</em> is the person who started the project. Creators are like trusted members but can never be banned or untrusted.</p>

<p>Note that <em>anyone</em> on the Web can view Pipeline projects, and any logged-in user can post comments and participate in discussions.</p>

<div class="line"> </div>

<h3 id="help-diff-task-contrib">What's the difference between tasks and contributions?</h3>

<p>A <em>task</em> is a job or bit of work that needs to be done within a project.</p>

<p>A <em>contribution</em> is someone's work on a task.</p>

<p>Every task has one <em>leader</em> and one or more <em>contributors</em>. Only trusted members can lead or create a task, but any logged-in user can contribute to a task.</p>

<div class="line"> </div>

<h3 id="help-lead-task">How do I lead a task?</h3>

<p>Only trusted members can be task leaders. If you are trusted, you can assign any trusted member (including yourself) to a new task. To change a task's leader, just edit the task and type a different user into the "Leader" field.</p>

<div class="line"> </div>

<h3 id="help-contrib-task">How do I contribute to a task?</h3>

<p>Any logged-in user can contribute to a task. First, you need to join the task. Find an open task and join it by clicking the "Join Task" button.</p>

<p>When you're ready to contribute, click the "Contribute" button on the task page and fill in the information. Click "Create Contribution" and you're done!</p>

<p>You can always revise your contribution by clicking the "Contribute" button again and posting a new contribution.</p>

<div class="line"> </div>

<h3 id="help-upload-formats">Which upload formats are supported?</h3>

<p>Currently, Pipeline supports:</p>

<ul>
	<li>Images (.jpg, .jpeg, .png, .gif, .psd)</li>
	<li>Video (.mov, .mpg, .mpeg, .avi)</li>
	<li>Audio (.mp3)</li>
	<li>Flash (.swf, .fla, .flv)</li>
	<li>Documents (.doc, .docx, .pdf)</li>
</ul>

<div class="line"> </div>

<h3 id="help-html-allowed">Which HTML tags can I use?</h3>

<p>If a textbox is marked "Some HTML allowed," you can use the following HTML tags: &lt;a&gt; &lt;strong&gt; &lt;b&gt; &lt;em&gt; &lt;i&gt;. Otherwise HTML isn't allowed.</p>

<div class="line"> </div>

<h3 id="help-diff-comment-disc">What's the difference between comments and discussions?</h3>

<p>Posts on tasks or contributions are called <em>comments</em>. Comments are best for leaving feedback on a specific task or contribution.</p>

<p>Pipeline also supports forum-style posts called <em>discussions</em>. Discussions are best for higher-level conversations that go beyond one task or contribution. Discussions can be categorized to appear in a certain tab (e.g. a discussion about inviting users could be categorized "People") or left uncategorized for project-level discussions.</p>

<div class="line"> </div>

<h3 id="help-project-status">What do the different project statuses mean?</h3>

<p>They can mean whatever you want. Here is one suggestion:</p>

<ul>
	<li><em>pre-production</em> means you are planning the project: setting up the basics, creating the first tasks, and inviting users.</li>
	<li><em>in production</em> means work has begun. Tasks have been assigned leaders and people are starting to make contributions.</li>
	<li><em>post-production</em> means most contributions are in and most tasks are closed. The final product is being assembled.</li>
	<li><em>finished</em> means the project has been completed and there's nothing left to do.</li>
	<li><em>canceled</em> means the project was abandoned and won't be completed.</li>
</ul>

<div class="line"> </div>

<h3 id="help-edit-profile">How do I edit my profile?</h3>

<p>Log in and click your username at the top. Your profile page will load. Click the "Edit" button at the top of the "Profile" box.</p>

<div class="line"> </div>

<h3 id="help-notify">How do I stop receiving Pipeline emails?</h3>

<p>You can customize which notification emails to receive (if any) by logging in and clicking the "Settings" link at the top.</p>

<div class="line"> </div>

<h3 id="help-questions">What if my question isn't answered here?</h3>

<p>Please <a href="mailto:<?= CONTACT_EMAIL ?>">shoot us an email</a> and we'll try to help.</p>

</div>

</td>

<td class="right"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');