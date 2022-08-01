<?php

namespace IranMap;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Users {

    public function save_user( $user_id ) {
        
        
        $iran_map_logo = $_REQUEST['iran_map_logo'];
        update_user_meta( $user_id , 'iran_map_logo' , $iran_map_logo );
        
        

    }
    
    public function render_template( $user ) {
        
        $user_id = $user->ID;
        $user_meta = get_user_meta( $user_id  , 'iran_map_logo' , true );

        ?>
        <h3 class="heading"> نقشه ایران </h3>
        <table>
            <tr>
                <th>لینک تصویر</th>
                <td>
                    <input type="text" name="iran_map_logo" value="<?php echo $user_meta; ?>" />
                </td>
            </tr>
        </table>

        <?php

    }



}