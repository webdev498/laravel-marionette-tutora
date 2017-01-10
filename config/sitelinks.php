<?php

return [
	

	'default_subjects' => [
		'maths' 	=> [],
		'english'	=> [],
		'combined-sciences' => [],
		'biology' => [],
		'chemistry' => [],
		'physics' => [],
		'french' => [], 
		'spanish' => [],
		'history' => [],
		'psychology' => [],
		'economics' => [],
		'german' => [],
		'mandarin' => [],
		'eleven-plus-11' => [],
	],

	'short_subjects' => [
		'maths' 	=> [],
		'english'	=> [],
		'combined-sciences' => [],
		'biology' => [],
		'chemistry' => [],
		'physics' => [],
		'french' => [], 
		'spanish' => []
	],

	'v_short_subjects' => [
		'maths' => [],
		'english' => [],
		'combined-sciences' => [],
	],

	'none' => [],

	'expanded_subjects' => [
		'maths' 	=> [],
		'further-maths' => [],
		'mechanics' => [],
		'statistics' => [],

		'english'	=> [],
		'english-literature' => [],
		
		'combined-sciences' => [],
		'biology' => [],
		'chemistry' => [],
		'physics' => [],
		'geology' => [],

		'history' => [],
		'geography' => [],
		'philosophy' => [],
		'psychology' => [],
		'sociology' => [],
		'economics' => [],
		'politics' => [],
		'law' => [],
		'business-studies' => [],

		'arabic' => [],
		'french' => [], 
		'spanish' => [],
		'german' => [],
		'mandarin' => [],
		'japanese' => [],
		'latin' => [],

		'eleven-plus-11' => [],
	],

	//All locations offered for tuition.

	'locations' => [
		
		'north-east' => [
			'barnsley' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'bradford' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'bridlington' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			'chesterfield' => [
				'subjects' =>'v_short_subjects',
				'level' => 'top',
			],

			'dewsbury' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'dronfield' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'grimsby' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],

			'halifax' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'huddersfield' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'hull' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'keighley' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],


			'leeds' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],
			'rotherham' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'scunthorpe' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			'sheffield' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'york' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			
		],
		'north-west' => [
			
			'altrincham' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],

			'blackburn' => [
				'subjects' => 'v_short_subjects',
				'level' => 'top',
			],
			'blackpool' => [
				'subjects' =>'v_short_subjects',
				'level' => 'top',
			],
			'bolton' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'burnley' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'bury' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'chester' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'leigh' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'liverpool' => [
				'subjects' =>'default_subjects',
				'level' => 'top',			
			],	

			'macclesfield' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'manchester' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'oldham' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],	

			'preston' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'rochdale' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],		

			'runcorn' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],

			'salford' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'southport' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			'stockport' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'warrington' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],
			'wigan' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],
			'wirral' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],


		],

		'north' => [
			
			'darlington' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'durham' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],


			'hartlepool' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'middlesbrough' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'newcastle upon tyne' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'sunderland' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],


		],

		'midlands' => [
			'birmingham' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'cannock' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'coventry' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'derby' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'dudley' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'hereford' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],
			
			'kidderminster' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'leamington' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			
			'leicester' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'loughborough' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],


			'nottingham' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'nuneaton' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'peterborough' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'rugby' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'shrewsbury' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'solihull' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'stafford' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'stoke' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'telford' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			'walsall' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'warwick' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'wolverhampton' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],
			'worcester' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],




		],

		'scotland' => [
			'aberdeen' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'dundee' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'edinburgh' => [
				'subjects' =>'expanded_subjects',
				'level' => 'top',
			],

			'falkirk' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'glasgow' => [
				'subjects' => 'expanded_subjects',
				'level' => 'top',
			],
			'cumbernauld' => [
				'subjects' => 'short_subjects',
				'level' => 'bottom',
			],
			

		],

		'south-west' => [

			'bournemouth' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'bath' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'bristol' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],

			'cheltenham' => [
				'subjects' =>'none',
				'level' => 'top',
			],


			'gloucester' => [
				'subjects' =>'none',
				'level' => 'top',
			],

			'poole' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',		
			],
			'plymouth' => [
				'subjects' =>'short_subjects',
				'level' => 'top',	
			],
			'swindon' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'weymouth' => [
				'subjects' =>'none',
				'level' => 'top',
			],


		],

		'south-east' => [
			
			'aldershot' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'basingstoke' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],
			'brighton' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'cambridge' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'canterbury' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'crawley' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'dunstable' => [
				'subjects' => 'none',
				'level' => 'bottom',
			],

			'eastbourne' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],

			'farnborough' => [
				'subjects' =>'v_short_subjects',
				'level' => 'bottom',
			],

			'guildford' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'high wycombe' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'ipswich' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'london' => [
				'subjects' =>'expanded_subjects',
				'level' => 'top',
			],

			'luton' => [
				'subjects' => 'short_subjects',
				'level' => 'top',
			],

			'margate' => [
				'subjects' =>'none',
				'level' => 'bottom',
			],

			'milton-keynes' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],

			'norwich' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'oxford' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'portsmouth' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'reading' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'slough' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'southampton' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'southend' => [
				'subjects' =>'short_subjects',
				'level' => 'bottom',
			],
		],


		'london' => [
			
			'barnet' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			'bexley' => [ 
				'subjects' =>'none',
				'level' => 'top',
			],
			'bromley' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			'croydon' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			
			'enfield' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			'hackney' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			
			'harrow' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			
			'hounslow' => [
				'subjects' =>'none',
				'level' => 'top',
			],
		
			'kingston upon thames' => [
				'subjects' =>'none',
				'level' => 'top',
			],
			
			'sutton' => [
				'subjects' =>'none',
				'level' => 'top',
			],
	
		],
		'wales' => [
			'cardiff' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],
			'swansea' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			],
			'newport' => [
				'subjects' =>'short_subjects',
				'level' => 'top',
			]

		],

		'northern ireland' => [
			'belfast' => [
				'subjects' =>'default_subjects',
				'level' => 'top',
			],
			

		],

		
		

	],


];