<div class="row">
    <br><br><br>
    <div class="container">
        <h1 class="level-1 rectangle">CEO</h1>
        <ol class="level-2-wrapper">
            <li>
                <h2 class="level-2 rectangle">Director A</h2>
                <ol class="level-3-wrapper">
                    <li>
                        <h3 class="level-3 rectangle"> Reports to -
                            {{ (!empty($employee->manager_first_name . ' ' . $employee->manager_surname))  ?
                                $employee->manager_first_name . ' ' . $employee->manager_surname : '' }}</h3>
                        <ol class="level-4-wrapper">
                            <li>
                                <h4 class="level-4 rectangle">
                                    {{ (!empty($employee->first_name . ''. $employee->surname)) ?
                               $employee->first_name . ''. $employee->surname : '' }}
                                </h4>
                            </li>

                        </ol>
                    </li>
                    <li>
                        <h3 class="level-3 rectangle"> Second Manager -
                            {{ (!empty($employee->second_manager_first_name . ''. $employee->second_manager_surname)) ?
                                $employee->second_manager_first_name . ''. $employee->second_manager_surname : '' }}
                        </h3>
                        <ol class="level-4-wrapper">
                            <li>
                                <h4 class="level-4 rectangle">
                                    {{ (!empty($employee->first_name . ''. $employee->surname)) ?
                               $employee->first_name . ''. $employee->surname : '' }}
                                </h4>
                            </li>

                        </ol>
                    </li>
                </ol>
            </li>

        </ol>
    </div>
</div>




