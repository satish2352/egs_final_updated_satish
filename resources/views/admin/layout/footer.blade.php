{{-- <script src="{{ asset('assets/js/jquery-3.7.0.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    /*window.addEventListener('load', e => {
  if ('serviceWorker' in navigator) {
    try{
      
      navigator.serviceWorker.register("service-worker.js");
      console.log('sw registered');
    } catch(error){
      console.log('sw reg failed');
    }
  }
});*/


    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
        $("#success-alert").slideUp(500);
        $("#danger-alert").slideUp(500);
    });
</script>


<!-- content-wrapper ends -->
<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"> © <script>
            document.write(new Date().getFullYear())
          </script>. All rights reserved with
            Admin</span>
        {{-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Powered by Software
            Solutions Private Limited</span> --}}
    </div>
</footer>
</div>
<!-- main-panel ends -->
</div>
</div>
<!-- global js -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/custom-new.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/calendar.js') }}"></script>
<script src="{{ asset('assets/js/calendar-docs.js') }}"></script>
<script src="{{ asset('assets/js/morris.js') }}"></script>
<script src="{{ asset('assets/js/progress-bar.js') }}"></script>
<script src="{{ asset('assets/js/profile-demo.js') }}"></script>
<script src="{{ asset('assets/js/flot-chart.js') }}"></script>
<script scr="js/chart.js"></script>
<script src="{{ asset('assets/js/c3.js') }}"></script>
<script src="{{ asset('assets/js/formpickers.js') }}"></script>
<script src="{{ asset('assets/js/wizard.js') }}"></script>


<script>
    $("select.non-food-select").change(function() {
        var selectedOption = $(this).children("option:selected").val();
        //alert("You have selected the country - " + selectedCountry);
        $(".declare-field").show();
    });


    $(".btn-user-approve").click(function() {
        $(".modal-approve").hide();
        $(".modal-backdrop").hide();
    });

    $(".btn-product-form").click(function() {
        $(".food-product-form").slideDown();
    });

    $(".calender-pop-close").click(function() {
        $(".calender-pop-area").hide();
    });

    $(".btn-product-save").click(function() {
        // $(".food-product-form").addClass("d-none");
        $(".food-product-form").slideUp();
    });


    $(".major").click(function() {
        $("#major").slideToggle("slow", function() {
            // Animation complete.
        });
    });

    if ($("#morris-donut-example").length) {
        Morris.Donut({
            element: 'morris-donut-example',
            colors: ['#FF5E6D ', '#63CF72', '#F5A623 ', '#FABA66'],
            width: 300,
            data: [{
                    label: "NOC",
                    value: 50
                },
                {
                    label: "P-NOC",
                    value: 30
                },
                {
                    label: "NCC",
                    value: 20
                }
            ]
        });
    }

    $("#year_picker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        year: 2017
    });

    var g8 = new JustGage({
        id: 'g8',
        value: 600,
        min: 0,
        max: 1000,
        reverse: true,
        gaugeWidthScale: 0.6,
        counter: true
    });

    //   (function ($) {
    //     "use strict";
    //     $('.incentive-trigger').on('click', function () {
    //         $(this).parent().toggleClass('switcher-palate');
    //     });

    // }(jQuery));


    $(document).ready(function() {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        var current = 1;
        var steps = $("fieldset").length;

        setProgressBar(current);

        $(".next").click(function() {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //Add Class Active
            //$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
            $(".progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 500
            });
            setProgressBar(++current);
        });

        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            //$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
            $(".progressbar li").eq($("fieldset").index(next_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 500
            });
            setProgressBar(--current);
        });

        function setProgressBar(curStep) {
            var percent = parseFloat(100 / steps) * curStep;
            percent = percent.toFixed();
            $(".progress-bar")
                .css("width", percent + "%")
        }

        $(".submit").click(function() {
            return false;
        })

    });
</script>

<script>
    //after window is loaded completely 
    window.onload = function() {
        //hide the preloader
        document.querySelector(".preloader").style.display = "none";
    }
</script>
<!-- New Pro Code  -->


<script>
    $('.delete-btn').click(function(e) {

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#delete_id").val($(this).attr("data-id"));
                $("#deleteform").submit();
            }
        })

    });
</script>

<script>
    $('.show-btn').click(function(e) {
        $("#show_id").val($(this).attr("data-id"));
        $("#showform").submit();
    })
</script>
{{-- <script>
    $('.edit-btn').click(function(e) {
    
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#edit_id").val($(this).attr("data-id"));
                $("#editform").submit();
    
                // Swal.fire(
                //     'Deleted!',
                //     'Your file has been deleted.',
                //     'success'
                // )
            }
        })
    
    });
    </script> --}}


<script>
   // $('.edit-btn').click(function(e) {
   //     $("#edit_id").val($(this).attr("data-id"));
    //    $("#editform").submit();
    //})
</script>

<script>
    $('.edit-user-btn').click(function(e) {
        $("#edit_user_id").val($(this).attr("data-id"));
        $("#edituserform").submit();
    })
</script>

<script>
    $('.active-btn').click(function(e) {
        $("#active_id").val($(this).attr("data-id"));
        $("#activeform").submit();
    })
</script>

<script type="text/javascript">
    function submitRegister() {
        document.getElementById("frm_register").submit();
    }
</script>



<!-- Summernote Editor -->

{{-- <script>
    $('#summernote').summernote({
    placeholder: 'Hello Bootstrap 4',
    tabsize: 2,
    height: 100
    });
</script> --}}

<!-- Summernote Editor End -->
<script>
    $(document).ready(() => {
        $("#english_image").change(function() {
            $('#english').css('display', 'none');
            $("#english_imgPreview").show();

            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#english_imgPreview")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(() => {
        $("#marathi_image").change(function() {
            $('#marathi').css('display', 'none');
            $("#marathi_imgPreview").show();
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#marathi_imgPreview")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(() => {
        $("#english_image_new").change(function() {
            $('#english1').css('display', 'none');
            $("#english_imgPreview1").show();

            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#english_imgPreview1")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(() => {
        $("#marathi_image_new").change(function() {
            $('#marathi1').css('display', 'none');
            $("#marathi_imgPreview1").show();
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#marathi_imgPreview1")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#order-listing').DataTable({
            searching: true,
            ordering: true,
            lengthChange: false,
            showNEntries: false
        });
    });
</script>

<script>
    $(function() {
        var option = function(i, j) {
            return $("<option>").append(j + 1980);
        };

        var options = (new Array(150) + "").split(",").map(option);

        $("#dYear").append(options);
    });
</script>
<script>
    $(document).ready(function() {
        $('.password_confirmation').on('input', function() {
            var password = $('.password').val();
            var confirmPassword = $(this).val();
            var errorSpan = $('.password-error');

            if (password !== confirmPassword) {
                errorSpan.text('Password does not match.');
            } else {
                errorSpan.text('');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#password_confirmation').on('input', function() {
            var password = $('#password').val();
            var confirmPassword = $(this).val();
            var errorSpan = $('#password-error');

            if (password !== confirmPassword) {
                errorSpan.text('Password does not match.');
            } else {
                errorSpan.text('');
            }
        });
    });
</script>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementsByClassName("password")[0];
        var toggleIcon = document.querySelector(".togglePpassword i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>
<script>
    function toggleConfirmPasswordVisibility() {
        var passwordInput = document.getElementsByClassName("password_confirmation")[0];

        var toggleIcon = document.querySelector(".toggleConfirmPpassword i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('.sidebar .nav-link').forEach(function(element){
    
    element.addEventListener('click', function (e) {

      let nextEl = element.nextElementSibling;
      let parentEl  = element.parentElement;	

        if(nextEl) {
            e.preventDefault();	
            let mycollapse = new bootstrap.Collapse(nextEl);
            
            if(nextEl.classList.contains('show')){
              mycollapse.hide();
            } else {
                mycollapse.show();
                // find other submenus with class=show
                var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                // if it exists, then close all of them
                if(opened_submenu){
                  new bootstrap.Collapse(opened_submenu);
                }
            }
        }
    }); // addEventListener
  }) // forEach
}); 
// DOMContentLoaded  end

    </script>
</body>

</html> <!-- partial:partials/_footer.html -->
