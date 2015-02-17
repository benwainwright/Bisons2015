<?php 

function join_as_supporter_shortcode()
{
    
    wp_enqueue_script('dynamicforms');
    wp_enqueue_script('formvalidation');
    $return = <<<OUTPUT
<form method="post">
    <fieldset>
        <legend>Personal Details</legend>
        <div>
            <label class="smalllabel" for="firstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="firstname" id="firstname" >
        </div>
        <div>
            <label class="smalllabel" for="surname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="surname" id="surname" >
        </div>
        <div>
            <label>Gender</label>
            <select class="mustselect" name="gender" id="gender" >
                <option></option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>
        <div id="othergender">
            <label class="smalllabel" for="othergender">Other Gender Details</label>
            <input type="text" class="smalltextbox notempty" name="othergender" >
            <p class="forminfo">As a fully inclusive rugby club, we completely recognise that a gender designation of 'male' or 'female' is far too simplistic for the real world. However, because we are a rugby team, we are bound by <a href="http://www.rfu.com/" title="RFU Website">RFU</a> regulations which unfortunately are categorised in simple male/female terms. Please be aware therefore that only a person who self-identifies as 'male' in some way can play in 'male' rugby. Likewise, only a person who self-identifies as 'female' in some way can play in 'female' rugby.</p>
        </div>
        <div>
            <label class="smalllabel" for="dob">Date of Birth</label>
             <div class="inlinediv">
             <select class="norightmargin" id="dob-day" name="dob-day">
                    <option value="0"></option>
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                    <option value="3">3rd</option>
                    <option value="4">4th</option>
                    <option value="5">5th</option>
                    <option value="6">6th</option>
                    <option value="7">7th</option>
                    <option value="8">8th</option>
                    <option value="9">9th</option>
                    <option value="10">10th</option>
                    <option value="11">11th</option>
                    <option value="12">12th</option>
                    <option value="13">13th</option>
                    <option value="14">14th</option>
                    <option value="15">15th</option>
                    <option value="16">16th</option>
                    <option value="17">17th</option>
                    <option value="18">18th</option>
                    <option value="19">19th</option>
                    <option value="20">20th</option>
                    <option value="21">21st</option>
                    <option value="22">22nd</option>
                    <option value="23">23rd</option>
                    <option value="24">24th</option>
                    <option value="25">25th</option>
                    <option value="26">26th</option>
                    <option value="27">27th</option>
                    <option value="28">28th</option>
                    <option value="29">29th</option>
                    <option value="30">30th</option>
                    <option value="31">31st</option>
                </select>
             <select class="norightmargin" id="dob-month" name="dob-month">
                    <option value="0"></option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            <select class="norightmargin" id="dob-year" name="dob-year">
                <option value="0"></option>
                                <option>1901</option>
                                <option>1902</option>
                                <option>1903</option>
                                <option>1904</option>
                                <option>1905</option>
                                <option>1906</option>
                                <option>1907</option>
                                <option>1908</option>
                                <option>1909</option>
                                <option>1910</option>
                                <option>1911</option>
                                <option>1912</option>
                                <option>1913</option>
                                <option>1914</option>
                                <option>1915</option>
                                <option>1916</option>
                                <option>1917</option>
                                <option>1918</option>
                                <option>1919</option>
                                <option>1920</option>
                                <option>1921</option>
                                <option>1922</option>
                                <option>1923</option>
                                <option>1924</option>
                                <option>1925</option>
                                <option>1926</option>
                                <option>1927</option>
                                <option>1928</option>
                                <option>1929</option>
                                <option>1930</option>
                                <option>1931</option>
                                <option>1932</option>
                                <option>1933</option>
                                <option>1934</option>
                                <option>1935</option>
                                <option>1936</option>
                                <option>1937</option>
                                <option>1938</option>
                                <option>1939</option>
                                <option>1940</option>
                                <option>1941</option>
                                <option>1942</option>
                                <option>1943</option>
                                <option>1944</option>
                                <option>1945</option>
                                <option>1946</option>
                                <option>1947</option>
                                <option>1948</option>
                                <option>1949</option>
                                <option>1950</option>
                                <option>1951</option>
                                <option>1952</option>
                                <option>1953</option>
                                <option>1954</option>
                                <option>1955</option>
                                <option>1956</option>
                                <option>1957</option>
                                <option>1958</option>
                                <option>1959</option>
                                <option>1960</option>
                                <option>1961</option>
                                <option>1962</option>
                                <option>1963</option>
                                <option>1964</option>
                                <option>1965</option>
                                <option>1966</option>
                                <option>1967</option>
                                <option>1968</option>
                                <option>1969</option>
                                <option>1970</option>
                                <option>1971</option>
                                <option>1972</option>
                                <option>1973</option>
                                <option>1974</option>
                                <option>1975</option>
                                <option>1976</option>
                                <option>1977</option>
                                <option>1978</option>
                                <option>1979</option>
                                <option>1980</option>
                                <option>1981</option>
                                <option>1982</option>
                                <option>1983</option>
                                <option>1984</option>
                                <option>1985</option>
                                <option>1986</option>
                                <option>1987</option>
                                <option>1988</option>
                                <option>1989</option>
                                <option>1990</option>
                                <option>1991</option>
                                <option>1992</option>
                                <option>1993</option>
                                <option>1994</option>
                                <option>1995</option>
                                <option>1996</option>
                                <option>1997</option>
                                <option>1998</option>
                                <option>1999</option>
                                <option>2000</option>
                                <option>2001</option>
                                <option>2002</option>
                                <option>2003</option>
                                <option>2004</option>
                                <option>2005</option>
                                <option>2006</option>
                                <option>2007</option>
                                <option>2008</option>
                                <option>2009</option>
                                <option>2010</option>
                                <option>2011</option>
                                <option>2012</option>
                                <option>2013</option>
                            </select>
            </div>
        </div>
        <div>
            <label class="smalllabel" for="email_addy">Email</label>
            <input type="text" class="smalltextbox needemail" name="email_addy" id="email_addy">
        </div>
        <div>
            <label class="smalllabel" for="contact_number">Contact Number</label>
            <input type="text" class="smalltextbox needphonenum" name="contact_number" id="contact_number">
        </div>
    </fieldset>
    <fieldset>
        <legend>Home Address</legend>
        <div>
            <label class="smalllabel" for="streetaddyl1">Line 1</label>
            <input type="text" class="smalltextbox notempty" name="streetaddyl1" id="streetaddyl1">
        </div>
        <div>
            <label class="smalllabel" for="streetaddyl2">Line 2</label>
            <input type="text" class="smalltextbox" name="streetaddyl2" id="streetaddyl2">
        </div>
        <div>
            <label class="smalllabel" for="streetaddytown">Town</label>
            <input type="text" class="smalltextbox" name="streetaddytown" id="streetaddytown">
        </div>
        <div>
            <label class="smalllabel" for="postcode">Postcode</label>
            <input type="text" class="smalltextbox needpostcode" name="postcode" id="postcode">
        </div>
    </fieldset>
    <fieldset>
        <legend>Next of Kin</legend>
        <p class="info">This person will be contacted in case of emergencies.</p>
        <div>
            <label class="smalllabel" for="nokfirstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="nokfirstname" id="nokfirstname">
        </div>
        <div>
            <label class="smalllabel" for="noksurname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="noksurname" id="noksurname">
        </div>
        <div>
            <label class="smalllabel" for="nokrelationship">Relationship</label>
            <input type="text" class="smalltextbox notempty" name="nokrelationship" id="nokrelationship">
        </div>
       <div>
            <label class="smalllabel" for="nokcontactnumber">Phone Number</label>
            <input type="text" class="smalltextbox needphonenum" name="nokcontactnumber" id="nokcontactnumber">
        </div>
        <div>
            <label>Lives at same address</label>
            <select name="sameaddress" id="sameaddress">
                <option></option>
                <option>No</option>
                <option>Yes</option>
            </select>
        </div>
        <div id="nokaddygroup">
            <div>
                <label for="nokstreetaddy">Street address</label>
                <textarea class="notempty" name="nokstreetaddy" id="nokstreetaddy"></textarea>
            </div>
            <div>
                <label class="smalllabel" for="nokpostcode">Postcode</label>
                <input type="text" class="smalltextbox needpostcode" name="nokpostcode" id="nokpostcode">
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Other</legend>
        <div>
            <label for="howdidyouhear">How did you hear about The Bisons?</label>
            <textarea class="notempty" name="howdidyouhear" id="howdidyouhear"></textarea>
        </div>
        <div>
            <label for="whatcanyoubring">Is there anything you can bring to the Bisons?</label>
            <textarea name="whatcanyoubring" id="whatcanyoubring"></textarea>
            <p class="forminfo"><strong>Optional</strong> The Bisons is run by a team of dedicated volunteers and we are always looking for people with useful skills that could make the team even better. This doesn't have to be rugby related, for example: perhaps you are good at numbers and might be a potential treasurer, or you have some serious marketing skills to help us get the club name out there.</p>
        </div>
        <div>
            <label for="topsize">Top size</label>
            <select class="mustselect" name="topsize">
                <option></option>
                <option>Small</option>
                <option>Medium</option>
                <option>Large</option>
                <option>X-Large</option>
                <option>XX-Large</option>
                <option>XXX-Large</option>

            </select>
            <p class="forminfo">What size would you like your exclusive Bisons social top to be?</p>
        </div>
    </fieldset>


    <fieldset>
        <legend>Payment</legend>
        <p class="info">Please indicate how you will be paying your membership fees. Note that once you have saved this form, you will be redirected to GoCardless, who handle our Direct Debits. Once they are done with you, you will be returned to our website afterwards.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Method</label>
            <select class="mustselect" name="paymethod" id="paymethod">
                <option></option>
                <option>Monthly Direct Debit</option>
                <option>Single Payment</option>
            </select>
        </div>
OUTPUT;

        $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'nopaging' => true ));
        while ( $fees->have_posts() ) 
        {
            $fees->the_post();
            
            $the_fee = array (
                'id'    => get_the_id(),
                'name' => get_post_meta( get_the_id(), 'fee-name', true),
                'initial-payment' => get_post_meta( get_the_id(), 'initial-payment', true),
                'amount' => get_post_meta( get_the_id(), 'fee-amount', true),
                'description' => get_post_meta( get_the_id(), 'fee-description', true)
            );
            
            
            if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit" )
            {
                  $supporterfees[ 'direct_debits' ] [ ] = $the_fee;
            }
            else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) != "Monthly Direct Debit")
            {
			$supporterfees[ 'single_payments' ] [ ] = $the_fee;
            }
        }
        $return .=<<<OUTPUT2
	  <div id="supporterfees" class='supportersonly'>
        <div id="supportermempaymonthly" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypemonthly" id="supportermembershiptypemonthly">
                <option></option>
OUTPUT2;
        foreach ($supporterfees[ 'direct_debits' ] as $fee) 
        {
            $return .= "<option value=\"".$fee['id']."\">".$fee['name']."</option>";
        }
            $return .= "</select><ul class='feeslist'>";
        foreach ($supporterfees[ 'direct_debits' ] as $fee)
        {
            $return .= "<li><strong>".$fee['name']."</strong><br />An initial payment of ".pence_to_pounds ( $fee['initial-payment'] ) ." and monthly payments of ".pence_to_pounds ( $fee['amount'] ) .". ".$fee['description']."</li>";
        }
$return .=<<<OUTPUT3
             </ul>
        </div>
        <div id="supportermempaysingle" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypesingle">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypesingle" id="supportermembershiptypesingle">
                <option></option>
OUTPUT3;
        foreach ($supporterfees[ 'single_payments' ] as $fee) 
        {
            $return .= "<option value=\"".$fee['id']."\">".$fee['name']."</option>";
        }
            $return .= "</select><ul class='feeslist'>";
        foreach ($supporterfees[ 'single_payments' ] as $fee)
        {
            $return .= "<li><strong>".$fee['name']."</strong><br />A single payment of ".pence_to_pounds ( $fee['initial-payment'] ) .". ". $fee['description'] ."</li>";
        }
        $return .= "</ul></div></div></fieldset>";
    
    $return .= "</form>";
    return $return;
}

add_shortcode('joinAsSupporterForm', 'join_as_supporter_shortcode');