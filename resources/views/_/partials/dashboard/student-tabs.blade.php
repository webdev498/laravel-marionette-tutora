<div class="[ band band--ruled ]">
    <div class="wrapper">
        <div class="layout"><!--
                --><div class="layout__item">
                <div class="tal u-mt- ib">
                    <h4 class="delta u-m0 inline">
                        {{ $student->name }}
                    </h4>
                    <a href="{{ route('badmin.students.edit', ['uuid' => $student->uuid ]) }}">
                        <h6 class="inline pill pill--small u-ml {{ $tab == 'personal' ? '' : 'pill--neutral' }}">Personal Info</h6>
                    </a>
                    <a href="{{ route('badmin.students.billing', ['uuid' => $student->uuid ]) }}">
                        <h6 class="inline pill pill--small u--mv-- {{ $tab == 'billing' ? '' : 'pill--neutral'}} ">Billing</h6>
                    </a>
                    <a href="{{ route('badmin.students.messages', ['uuid' => $student->uuid ]) }}">
                        <h6 class="inline pill pill--small  u--mv-- {{ $tab == 'messages' ? '' : 'pill--neutral' }} ">Messages</h6>
                    </a>
                    <a href="{{ route('badmin.students.lessons', ['uuid' => $student->uuid ]) }}">
                        <h6 class="inline pill pill--small u--mv-- {{ $tab == 'lessons' ? '' : 'pill--neutral' }}">Lessons</h6>
                    </a>
                </div>

            </div><!--
            --></div>
    </div>
</div>
