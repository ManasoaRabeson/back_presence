<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Opportunité gagnée</title>
    <style>
        .h-128 {
            height: 22rem;
        }

        .content__header {
            background: #070D12 url(https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fGdhZ25lcnxlbnwwfHwwfHx8MA%3D%3D) no-repeat;
            background-position: center 1px;
            background-size: cover;
        }

        .button {
            position: relative;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            background: #A462A4;
            letter-spacing: 1px;
            border: 2px solid white;
            border-radius: 000px;
            padding: 10px 20px;
            margin: 40px;
            box-shadow: 0 2px 5px 0 rgba(3, 6, 26, 0.15);
            transition: .5s all ease-in-out;

            &:hover {
                cursor: pointer;
                background: white;
                color: #1F4141;
                animation: none;
                //animation-play-state: paused;
            }
        }

        /* .button-pulse {
            animation: pulse 2s infinite 3s cubic-bezier(0.25, 0, 0, 1);
            box-shadow: 0 0 0 0 white;
        }

        @keyframes pulse {
            to {
                box-shadow: 0 0 0 18px rgba(255, 255, 255, 0);
            }
        } */
    </style>
</head>

<body>
    <div class="app font-sans min-w-screen min-h-screen bg-grey-lighter py-8 px-4">

        <div class="mail__wrapper max-w-md mx-auto">

            <div class="mail__content bg-white p-8 shadow-md">

                <div
                    class="content__header h-128 flex flex-col items-center justify-center text-center tracking-wide leading-normal bg-black -mx-8 -mt-8">

                </div>

                <div class="content__body py-8 border-b">
                    <h3 class="text-center text-2xl sm:text-3xl pt-4 mb-8 ">Félicitations, <br>vous avez gagné une
                        opportunité ! 🎉</h3>
                    <p class="pt-4 leading-normal">
                        Salutation!<br><br> Bravo ! Vous avez obtenu un projet pour le compte de
                        <strong>{{ $etp_name }}</strong>, prévu à <strong>{{ $lieu }}</strong> le
                        <strong>{{ $dateDeb }}</strong> jusqu'au <strong>{{ $dateFin }}</strong>.<br><br> Pour
                        paramètrer ce projet, veuillez cliquer sur le
                        bouton ci-dessous. Vous serez redirigé dans le projet en question.
                    </p>
                    <a href="https://mg.forma-fusion.com/cfp/projets/{{ $idProjet }}/detail" target="_blank"
                        class="text-white button button-pulse text-sm tracking-wide rounded w-full my-8 p-4 ">Allez
                        dans le projet </a>
                    <p class="text-sm">
                        Equipe FormaFusion
                    </p>
                </div>

                <div class="content__footer mt-8 text-center text-grey-darker">
                    <h3 class="text-base sm:text-lg mb-4">Merci d'utiliser FormaFusion !</h3>
                    <!--         <p>www.theapp.io</p> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
