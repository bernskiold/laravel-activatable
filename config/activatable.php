<?php

return [

    /*
     * The boolean column that stores the active state of a model. This is the
     * default used by the trait, the factory states and the schema macros.
     * Individual models may override it with an `ACTIVE_COLUMN` constant.
     */
    'column' => 'is_active',

    /*
     * The value a model should default to when it is created without an
     * explicit active state. Most applications want new records to be active.
     */
    'default_active' => true,

    /*
     * Optionally track the moment a model was deactivated. When a model opts in
     * (see `track_inactivated_at`), this timestamp column is set on deactivation
     * and cleared again on activation.
     */
    'inactivated_at_column' => 'inactivated_at',

    /*
     * Whether models track the deactivation timestamp by default. Individual
     * models may override this with a `protected bool $tracksInactivatedAt`
     * property regardless of this value.
     */
    'track_inactivated_at' => false,

];
