define([
	'base',
	'entities/user',
	'apps/tutors/edit/views/tagline',
	'apps/tutors/edit/views/pic',
	'apps/tutors/edit/views/rate',
	'apps/tutors/edit/views/bio',
	'apps/tutors/edit/views/subjects',
	'apps/tutors/edit/views/qualifications',
	'apps/tutors/edit/views/distance',
	'apps/tutors/edit/views/travel_policy',
	'apps/tutors/edit/views/badges',
	'apps/tutors/edit/views/video'
], function (Base,
			 User,
			 TaglineView,
			 PicView,
			 RateView,
			 BioView,
			 SubjectsView,
			 QualificationsView,
			 DistanceView,
			 TravelPolicyView,
			 BadgesView,
			 VideoView) {

	return Base.Controller.extend({

		initialize: function (options) {
			this.app.addRegions({
				'taglineRegion': '.js-tagline',
				'picRegion': '.js-pic',
				'rateRegion': '.js-rate',
				'bioRegion': '.js-bio',
				'subjectsRegion': '.js-subjects',
				'qualificationsRegion': '.js-qualifications',
				'distanceRegion': '.js-distance',
				'travelPolicyRegion': '.js-travel-policy',
				'badgesRegion': '.js-badges',
				'videoRegion': '.js-video'
			});

			this.user = User.current();

			this.attachTagline();
			this.attachPic();
			this.attachRate();
			this.attachBio();
			this.attachSubjects();
			this.attachQualifications();
			this.attachVideo();
			this.renderDistance();
			this.renderTravelPolicy();
			this.renderBadges();
		},

		attachTagline: function () {
			var view = new TaglineView({
				el: this.app.taglineRegion.$el,
				model: this.user
			});

			this.app.taglineRegion.attachView(view);
		},

		attachPic: function () {
			var view = new PicView({
				'el': this.app.picRegion.$el,
				'model': this.user
			});

			this.app.picRegion.attachView(view);
		},

		attachRate: function () {
			var view = new RateView({
				el: this.app.rateRegion.$el,
				model: this.user
			});

			this.app.taglineRegion.attachView(view);
		},

		attachBio: function () {
			var view = new BioView({
				el: this.app.bioRegion.$el,
				model: this.user
			});

			this.app.bioRegion.attachView(view);
		},

		attachSubjects: function () {
			var view = new SubjectsView({
				el: this.app.subjectsRegion.$el,
				collection: this.user.subjects
			});

			this.app.subjectsRegion.attachView(view);
		},

		attachQualifications: function () {
			var view = new QualificationsView({
				el: this.app.qualificationsRegion.$el,
				model: this.user.qualifications
			});

			this.app.qualificationsRegion.attachView(view);
		},

		attachVideo: function () {
			var view = new VideoView({
				el: this.app.videoRegion.$el,
				model: this.user
			});

			this.app.videoRegion.attachView(view);
		},

		renderDistance: function () {
			var view = new DistanceView({
				'model': this.user
			});

			this.app.distanceRegion.show(view);
		},

		renderTravelPolicy: function () {
			var view = new TravelPolicyView({
				'model': this.user
			});

			this.app.travelPolicyRegion.show(view);
		},

		renderBadges: function () {
			var view = new BadgesView({
				'model': this.user
			});

			this.app.badgesRegion.show(view);
		}

	});

});
